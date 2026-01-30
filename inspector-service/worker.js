require('dotenv').config();
const Redis = require('ioredis');
const { chromium } = require('playwright');
const { expect } = require('@playwright/test');
const redis = new Redis({
    host: process.env.REDIS_HOST || '127.0.0.1',
    port: process.env.REDIS_PORT || 6379
});

const APP_URL = process.env.APP_URL || 'http://qraft.test';
// Laravel Redis Prefix: qraft-database-
const QUEUE_NAME = 'qraft-database-qraft:inspector:tasks';

console.log(`🕷️ Inspector Worker started. Connecting to Redis at ${process.env.REDIS_HOST}...`);
console.log(`Waiting for tasks on queue: ${QUEUE_NAME}`);

async function processTask(taskData) {
    const { run_id, steps, network_mocks } = taskData;
    console.log(`[Run #${run_id}] Processing ${steps ? steps.length : 0} steps...`);

    let browser = null;
    let result = {
        success: false,
        screenshot: null,
        logs: [],
        title: '',
    };

    try {
        browser = await chromium.launch({ headless: true });
        const context = await browser.newContext({
            viewport: { width: 1280, height: 800 }
        });
        const page = await context.newPage();

        // Setup Network Mocking if provided
        if (network_mocks && network_mocks.length > 0) {
            for (const mock of network_mocks) {
                if (mock.type === 'mock_api') {
                    await page.route(mock.url, route => {
                        route.fulfill({
                            status: mock.status || 200,
                            contentType: mock.content_type || 'application/json',
                            body: JSON.stringify(mock.response)
                        });
                    });
                    result.logs.push(`[Network Mock] API mocked: ${mock.url}`);
                } else if (mock.type === 'block_resource') {
                    await page.route(mock.pattern, route => route.abort());
                    result.logs.push(`[Network Mock] Resource blocked: ${mock.pattern}`);
                } else if (mock.type === 'throttle') {
                    // Simulate slow network
                    await context.route('**/*', route => {
                        setTimeout(() => route.continue(), mock.delay_ms || 1000);
                    });
                    result.logs.push(`[Network Mock] Network throttled: ${mock.delay_ms}ms`);
                }
            }
        }

        // Capture logs
        page.on('console', msg => {
            const type = msg.type();
            const text = msg.text();
            result.logs.push(`[Browser] ${type}: ${text}`);
        });

        // Capture network errors
        result.network_errors = [];
        page.on('requestfailed', request => {
            result.network_errors.push({
                type: 'request_failed',
                url: request.url(),
                error: request.failure()?.errorText || 'Unknown error',
                method: request.method()
            });
        });

        page.on('response', response => {
            const status = response.status();
            if (status >= 400) {
                result.network_errors.push({
                    type: 'http_error',
                    url: response.url(),
                    status: status,
                    statusText: response.statusText(),
                    method: response.request().method()
                });
            }
        });

        // Helper function to resolve selectors based on type
        async function getLocator(page, step) {
            const selectorType = step.selector_type || 'css';
            const selector = step.selector;

            switch (selectorType) {
                case 'xpath':
                    return page.locator(`xpath=${selector}`);
                case 'text':
                    return page.getByText(selector, { exact: step.exact !== false });
                case 'role':
                    // Parse role selector: role=button[name="Submit"]
                    const roleMatch = selector.match(/role=([^\[]+)(\[name="([^"]+)"\])?/);
                    if (roleMatch) {
                        const role = roleMatch[1];
                        const name = roleMatch[3];
                        return name ? page.getByRole(role, { name }) : page.getByRole(role);
                    }
                    return page.getByRole(selector);
                case 'testid':
                    return page.getByTestId(selector);
                case 'placeholder':
                    return page.getByPlaceholder(selector);
                case 'label':
                    return page.getByLabel(selector);
                case 'ai_describe':
                    // AI-powered element discovery
                    const screenshot = await page.screenshot({ encoding: 'base64' });
                    try {
                        const response = await fetch(`${process.env.APP_URL || 'http://qraft.test'}/api/ai/find-element`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                screenshot: screenshot,
                                description: selector
                            })
                        });
                        const aiResult = await response.json();
                        if (aiResult.found) {
                            result.logs.push(`[AI Discovery] Found "${selector}" -> ${aiResult.selector} (confidence: ${aiResult.confidence})`);
                            return page.locator(aiResult.selector);
                        } else {
                            throw new Error(`AI could not find: ${selector}. Reason: ${aiResult.reasoning}`);
                        }
                    } catch (error) {
                        result.logs.push(`[AI Discovery Error] ${error.message}`);
                        throw error;
                    }
                case 'css':
                default:
                    return page.locator(selector);
            }
        }

        if (!steps || steps.length === 0) {
            throw new Error("No steps defined for this run.");
        }

        for (const [index, step] of steps.entries()) {
            const stepNum = index + 1;
            console.log(`[Run #${run_id}] Step ${stepNum}: ${step.action}`);
            result.logs.push(`[Step ${stepNum}] Executing ${step.action}...`);

            try {
                switch (step.action) {
                    case 'visit':
                        await page.goto(step.value, { waitUntil: 'networkidle', timeout: 30000 });
                        break;

                    case 'click':
                        const clickLocator = getLocator(page, step);
                        await clickLocator.click({ timeout: 5000 });
                        break;

                    case 'type':
                        const typeLocator = getLocator(page, step);
                        await typeLocator.fill(step.value, { timeout: 5000 });
                        break;

                    case 'wait':
                        const ms = parseInt(step.value) || 1000;
                        await page.waitForTimeout(ms);
                        break;

                    case 'assert_text':
                        const assertTextLocator = getLocator(page, step);
                        await expect(assertTextLocator).toHaveText(step.value, { timeout: 5000 });
                        result.logs.push(`[Step ${stepNum}] Assertion Passed: "${step.selector}" contains "${step.value}"`);
                        break;

                    case 'assert_visible':
                        const assertVisibleLocator = getLocator(page, step);
                        await expect(assertVisibleLocator).toBeVisible({ timeout: 5000 });
                        result.logs.push(`[Step ${stepNum}] Assertion Passed: "${step.selector}" is visible`);
                        break;

                    case 'hover':
                        const hoverLocator = getLocator(page, step);
                        await hoverLocator.hover({ timeout: 5000 });
                        break;

                    case 'select':
                        const selectLocator = getLocator(page, step);
                        await selectLocator.selectOption(step.value, { timeout: 5000 });
                        break;

                    case 'check':
                        const checkLocator = getLocator(page, step);
                        await checkLocator.check({ timeout: 5000 });
                        break;

                    case 'uncheck':
                        const uncheckLocator = getLocator(page, step);
                        await uncheckLocator.uncheck({ timeout: 5000 });
                        break;

                    case 'screenshot':
                        // Handled at end, but we could support intermediate screenshots later
                        break;

                    default:
                        result.logs.push(`[Step ${stepNum}] Warning: Unknown action "${step.action}"`);
                }
            } catch (stepError) {
                console.error(`[Run #${run_id}] Step ${stepNum} Failed: ${stepError.message}`);
                result.logs.push(`[Step ${stepNum}] FAILED: ${stepError.message}`);
                throw stepError; // Stop execution on failure
            }
        }

        result.title = await page.title();

        // Take Screenshot (Full Page)
        const buffer = await page.screenshot({ fullPage: true, type: 'jpeg', quality: 80 });
        result.screenshot = buffer.toString('base64');
        result.success = true;

        console.log(`[Run #${run_id}] Success: ${result.title}`);

    } catch (error) {
        console.error(`[Run #${run_id}] Error:`, error.message);
        result.logs.push(`[System] Error: ${error.message}`);
    } finally {
        if (browser) await browser.close();
    }

    // Send Result to Laravel
    try {
        const webhookUrl = `${APP_URL}/api/webhooks/inspector`;
        const response = await fetch(webhookUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                run_id: run_id,
                result: result,
                secret: 'qraft-internal-secret' // Todo: secure this
            })
        });

        if (!response.ok) {
            console.error(`[Run #${run_id}] Failed to report results: ${response.status} ${response.statusText}`);
            const text = await response.text();
            console.error(text);
        } else {
            console.log(`[Run #${run_id}] Results reported successfully.`);
        }
    } catch (err) {
        console.error(`[Run #${run_id}] Network error reporting results:`, err.message);
    }
}

async function start() {
    while (true) {
        try {
            // BLPOP returns messages from the queue [key, value]
            const msg = await redis.blpop(QUEUE_NAME, 0);
            if (msg && msg[1]) {
                const task = JSON.parse(msg[1]);
                await processTask(task);
            }
        } catch (error) {
            console.error('Redis Error:', error);
            await new Promise(r => setTimeout(r, 5000)); // Backoff
        }
    }
}

start();
