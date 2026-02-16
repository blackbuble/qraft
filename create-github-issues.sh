#!/bin/bash

# GitHub Issues Creator for QRAFT
# This script helps you create all priority issues quickly

echo "🚀 QRAFT GitHub Issues Creator"
echo "================================"
echo ""
echo "This script will guide you through creating 8 priority issues."
echo "You'll need to be logged into GitHub in your browser."
echo ""
echo "Press Enter to continue..."
read

# Issue 1: Rate Limiting
echo ""
echo "📋 Issue #1: Rate Limiting on AI Endpoints"
echo "-------------------------------------------"
echo "Opening GitHub in browser..."
open "https://github.com/blackbuble/qraft/issues/new?title=%5BSecurity%5D%20Add%20Rate%20Limiting%20to%20AI%20Endpoints&labels=security,enhancement,high-priority"
echo ""
echo "Copy this description:"
echo ""
cat << 'EOF'
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
EOF
echo ""
echo "After pasting, press Enter to continue to next issue..."
read

# Issue 2: AI Error Handling
echo ""
echo "📋 Issue #2: Improve AI Error Handling"
echo "---------------------------------------"
echo "Opening GitHub in browser..."
open "https://github.com/blackbuble/qraft/issues/new?title=%5BBug%5D%20Improve%20Error%20Handling%20in%20AI%20Services&labels=bug,ai,ux"
echo ""
echo "Copy this description:"
echo ""
cat << 'EOF'
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
EOF
echo ""
echo "After pasting, press Enter to continue to next issue..."
read

# Issue 3: Test Coverage
echo ""
echo "📋 Issue #3: Add Comprehensive Test Coverage"
echo "---------------------------------------------"
echo "Opening GitHub in browser..."
open "https://github.com/blackbuble/qraft/issues/new?title=%5BEnhancement%5D%20Add%20Comprehensive%20Test%20Coverage%20(Target:%2080%25%2B)&labels=enhancement,testing,good-first-issue"
echo ""
echo "Copy this description:"
echo ""
cat << 'EOF'
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
EOF
echo ""
echo "After pasting, press Enter to continue to next issue..."
read

# Issue 4: Chrome Extension
echo ""
echo "📋 Issue #4: Build Chrome Extension Recorder"
echo "---------------------------------------------"
echo "Opening GitHub in browser..."
open "https://github.com/blackbuble/qraft/issues/new?title=%5BEnhancement%5D%20Build%20Chrome%20Extension%20for%20Test%20Recording&labels=enhancement,chrome-extension,recorder"
echo ""
echo "Copy this description:"
echo ""
cat << 'EOF'
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
EOF
echo ""
echo "After pasting, press Enter to continue to next issue..."
read

# Issue 5: Plan-Based Limits
echo ""
echo "📋 Issue #5: Implement Plan-Based Usage Limits"
echo "-----------------------------------------------"
echo "Opening GitHub in browser..."
open "https://github.com/blackbuble/qraft/issues/new?title=%5BEnhancement%5D%20Implement%20Plan-Based%20Usage%20Limits&labels=enhancement,monetization,user-management"
echo ""
echo "Copy this description:"
echo ""
cat << 'EOF'
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
EOF
echo ""
echo "After pasting, press Enter to continue..."
read

echo ""
echo "✅ Top 5 priority issues created!"
echo ""
echo "Would you like to create the remaining 3 issues? (y/n)"
read answer

if [ "$answer" = "y" ]; then
    # Issue 6: Loading States
    echo ""
    echo "📋 Issue #6: Add Loading States to AI Actions"
    echo "----------------------------------------------"
    open "https://github.com/blackbuble/qraft/issues/new?title=%5BGood%20First%20Issue%5D%20Add%20Loading%20States%20to%20AI%20Actions&labels=good-first-issue,ui,ux"
    echo ""
    echo "Copy this description:"
    echo ""
    cat << 'EOF'
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
EOF
    echo ""
    echo "Press Enter to continue..."
    read

    # Issue 7: Demo Seeder Fix
    echo ""
    echo "📋 Issue #7: Fix Demo Seeder to Include Test Runs"
    echo "--------------------------------------------------"
    open "https://github.com/blackbuble/qraft/issues/new?title=%5BBug%5D%20Demo%20Seeder%20Doesn't%20Create%20Run%20Records&labels=bug,seeder,demo-data"
    echo ""
    echo "Copy this description:"
    echo ""
    cat << 'EOF'
## Description
`DemoDataSeeder` creates projects and test scenarios but doesn't create any Run records.

## Current Behavior
Demo data has no test run history, making it hard to showcase run results and AI analysis features.

## Expected Behavior
- Create sample agents for each project
- Create realistic run history with varied statuses
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
EOF
    echo ""
    echo "Press Enter to continue..."
    read

    # Issue 8: API Documentation
    echo ""
    echo "📋 Issue #8: Add API Documentation with Swagger"
    echo "------------------------------------------------"
    open "https://github.com/blackbuble/qraft/issues/new?title=%5BDocumentation%5D%20Add%20API%20Documentation%20with%20Swagger&labels=documentation,api"
    echo ""
    echo "Copy this description:"
    echo ""
    cat << 'EOF'
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
EOF
    echo ""
fi

echo ""
echo "🎉 All issues created successfully!"
echo ""
echo "View your issues at: https://github.com/blackbuble/qraft/issues"
echo ""
