# Ready-to-Post GitHub Issues

Copy-paste these directly into GitHub Issues at: https://github.com/blackbuble/qraft/issues/new

---

## Issue #1: Rate Limiting on AI Endpoints

**Title**: `[Security] Add Rate Limiting to AI Endpoints`

**Labels**: `security`, `enhancement`, `high-priority`

**Body**:
```markdown
## Description
AI endpoints (`/api/ai/find-element`, `/api/ai/heal-selector`) currently lack rate limiting, allowing potential abuse and high API costs.

## Current Behavior
- Unlimited requests to AI endpoints
- No protection against abuse
- Potential for high costs from malicious usage

## Expected Behavior
- Rate limit: 10 requests/minute per user
- Return 429 status with retry-after header
- Track usage in database
- Admin alerts for suspicious activity

## Acceptance Criteria
- [ ] Implement rate limiting middleware (10 req/min per user)
- [ ] Return 429 with retry-after header
- [ ] Track usage in database
- [ ] Send alerts for abuse patterns

## Files to Modify
- `routes/api.php`
- New: `app/Http/Middleware/AiRateLimiter.php`

## Priority
🔴 High - Security and cost control
```

---

## Issue #2: Improve AI Error Handling

**Title**: `[Bug] Improve Error Handling in AI Services`

**Labels**: `bug`, `ai`, `ux`

**Body**:
```markdown
## Description
AI services show generic error messages without specific guidance for different failure modes.

## Current Behavior
Generic errors like "Failed to generate test" without context.

## Expected Behavior
- Specific error messages for different failures:
  - API rate limiting (429)
  - Invalid API keys (401)
  - Network timeouts
  - Malformed responses
- Retry logic for transient failures
- User-friendly suggestions

## Acceptance Criteria
- [ ] Handle 429 rate limit with exponential backoff
- [ ] Detect invalid API keys with setup instructions
- [ ] Implement timeout handling (max 30s)
- [ ] Add user-friendly Filament notifications

## Files Affected
- `app/Services/AiTestGeneratorService.php`
- `app/Services/AiElementService.php`
- `app/Http/Controllers/Api/AiElementController.php`

## Priority
🔴 High - User experience
```

---

## Issue #3: Add Comprehensive Test Coverage

**Title**: `[Enhancement] Add Comprehensive Test Coverage (Target: 80%+)`

**Labels**: `enhancement`, `testing`, `good-first-issue`

**Body**:
```markdown
## Description
Project currently has only example tests. Need comprehensive test suite.

## Target Coverage
80%+ code coverage

## Proposed Tests

### Unit Tests
- `AiTestGeneratorServiceTest` - Prompt building, parsing, validation
- `AiElementServiceTest` - Element finding, selector healing  
- `UsageLimitServiceTest` - Limit enforcement

### Feature Tests
- `TestExecutionTest` - End-to-end test run flow
- `AiElementControllerTest` - API endpoints
- `InspectorWebhookTest` - Webhook handling

### Integration Tests
- `FullTestRunTest` - Complete flow from creation to results

## Acceptance Criteria
- [ ] Achieve 80%+ code coverage
- [ ] All services have unit tests
- [ ] All API endpoints have feature tests
- [ ] CI/CD pipeline runs tests automatically

## Good First Issue
✨ This is a great opportunity for contributors to learn the codebase!

## Priority
🔴 High - Code quality
```

---

## Issue #4: Implement Plan-Based Usage Limits

**Title**: `[Enhancement] Implement Plan-Based Usage Limits`

**Labels**: `enhancement`, `monetization`, `user-management`

**Body**:
```markdown
## Description
User model has TODO comment for plan-based limits. Need to implement usage tracking and enforcement for monetization.

**Reference**: `app/Models/User.php` line 68

## Proposed Features
- Track test runs per month
- Limit AI requests per plan tier
- Enforce concurrent test execution limits
- Show usage dashboard in Filament

## Plan Tiers
- **Free**: 10 test runs/month, 20 AI requests
- **Pro**: 500 test runs/month, 1000 AI requests
- **Enterprise**: Unlimited

## Acceptance Criteria
- [ ] Define plan tiers (Free, Pro, Enterprise)
- [ ] Track monthly test runs and AI requests
- [ ] Enforce limits before job execution
- [ ] Show usage stats in user dashboard
- [ ] Send email notifications at 80% and 100% usage

## Files to Create/Modify
- `app/Models/User.php` (line 68 - remove TODO)
- New: `app/Services/UsageLimitService.php`
- New: `database/migrations/*_add_usage_tracking_to_users.php`

## Priority
🟡 Medium - Monetization ready
```

---

## Issue #5: Chrome Extension Recorder

**Title**: `[Enhancement] Build Chrome Extension for Test Recording`

**Labels**: `enhancement`, `chrome-extension`, `recorder`

**Body**:
```markdown
## Description
Build Chrome extension to record user interactions and auto-generate test scenarios.

## Features
- Record clicks, typing, navigation
- Generate QRAFT-compatible test steps
- AI-powered selector optimization
- One-click export to QRAFT

## Technical Stack
- Manifest V3 Chrome Extension
- Content script for DOM interaction
- Background service worker for processing
- Integration with QRAFT API

## Acceptance Criteria
- [ ] Record basic actions (click, type, navigate)
- [ ] Generate optimal selectors (ID > class > xpath)
- [ ] Export to QRAFT JSON format
- [ ] Publish to Chrome Web Store

## Competitive Advantage
🚀 This is a **major differentiator** vs Playwright/Selenium!

## Priority
🔴 High - Competitive advantage
```

---

## Issue #6: Fix Demo Seeder to Include Test Runs

**Title**: `[Bug] Demo Seeder Doesn't Create Run Records`

**Labels**: `bug`, `seeder`, `demo-data`

**Body**:
```markdown
## Description
`DemoDataSeeder` creates projects and test scenarios but doesn't create any Run records due to missing `project_id` and `agent_id` requirements.

## Current Behavior
Demo data has no test run history, making it hard to showcase run results and AI analysis features.

## Expected Behavior
- Create sample agents for each project
- Create realistic run history with varied statuses (success, failed, running)
- Include AI analysis results in some runs

## Acceptance Criteria
- [ ] Create 1-2 agents per project
- [ ] Create 5-10 runs per project with realistic timestamps
- [ ] Include varied statuses (pending, running, success, failed)
- [ ] Add AI analysis to successful runs
- [ ] Add screenshots to some runs

## Files Affected
- `database/seeders/DemoDataSeeder.php`

## Priority
🟢 Low - Demo improvement
```

---

## Issue #7: Add Loading States to AI Actions

**Title**: `[Good First Issue] Add Loading States to AI Actions`

**Labels**: `good-first-issue`, `ui`, `ux`

**Body**:
```markdown
## Description
AI actions (Generate Steps, Find Element) don't show loading indicators, making users think the app is frozen.

## What to Do
1. Add loading spinner to "Generate Steps" button
2. Disable button during generation
3. Show progress text ("Generating steps...")
4. Re-enable button when complete

## Files to Modify
- `app/Filament/Resources/TestScenarioResource.php` (line 201-250)

## Helpful Resources
- [Filament Actions Documentation](https://filamentphp.com/docs/3.x/actions/overview)
- Look for `->action(function ...)` and add loading state

## Estimated Time
⏱️ 30 minutes

## Good First Issue
✨ Perfect for first-time contributors!
```

---

## Issue #8: Add API Documentation with Swagger

**Title**: `[Documentation] Add API Documentation with Swagger`

**Labels**: `documentation`, `api`

**Body**:
```markdown
## Description
API endpoints (`/api/ai/find-element`, `/api/ai/heal-selector`) lack documentation.

## What to Do
1. Install `darkaonline/l5-swagger`
2. Add PHPDoc annotations to controllers
3. Generate Swagger UI
4. Add authentication guide

## Acceptance Criteria
- [ ] Swagger UI accessible at `/api/documentation`
- [ ] All endpoints documented
- [ ] Request/response examples included
- [ ] Authentication flow explained

## Files to Modify
- `app/Http/Controllers/Api/AiElementController.php`
- New: `config/l5-swagger.php`

## Priority
🟡 Medium - Developer experience
```

---

## How to Create These Issues

1. Go to: https://github.com/blackbuble/qraft/issues/new
2. Copy the **Title** and paste it
3. Copy the **Body** (everything in the markdown block)
4. Add the **Labels** manually
5. Click "Submit new issue"

## Recommended Order

Create in this priority order:
1. ✅ Issue #1: Rate Limiting (Security)
2. ✅ Issue #2: AI Error Handling (UX)
3. ✅ Issue #3: Test Coverage (Quality)
4. ✅ Issue #5: Chrome Extension (Competitive advantage)
5. ✅ Issue #4: Plan Limits (Monetization)
6. Issue #7: Loading States (Good first issue)
7. Issue #6: Demo Seeder Fix
8. Issue #8: API Documentation

---

**Total Issues Ready**: 8  
**Priority**: 5 High, 2 Medium, 1 Low  
**Good First Issues**: 1
