# AI Test Generation

Learn how to use QRAFT's AI-powered test generation to create tests in seconds.

## 🤖 Overview

QRAFT's AI Test Generation uses Google Gemini to convert natural language descriptions into executable Playwright tests.

**Benefits:**
- ⚡ **20-30x faster** than manual test writing
- 🎯 **No coding required** - describe in plain English
- 🔄 **Iterative refinement** - AI learns from feedback
- 📝 **Best practices** - AI follows testing standards

## 🚀 Quick Start

### 1. Navigate to Test Scenarios

In your QRAFT dashboard:
1. Select your project
2. Click "Test Scenarios"
3. Click "Create" button

### 2. Describe Your Test

Click "🤖 Generate Steps" and describe what you want to test:

**Example 1: Login Test**
```
Test user login flow:
- Visit the login page
- Enter email: user@example.com
- Enter password: password123
- Click login button
- Verify dashboard is displayed
```

**Example 2: E-commerce Checkout**
```
Test product purchase:
- Search for "laptop"
- Click first result
- Add to cart
- Go to checkout
- Fill shipping information
- Complete payment
- Verify order confirmation
```

**Example 3: Form Validation**
```
Test registration form validation:
- Visit signup page
- Submit empty form
- Verify error messages appear
- Fill valid data
- Submit form
- Verify success message
```

### 3. Review Generated Steps

AI will generate:
- Step-by-step test actions
- Selectors for elements
- Assertions for verification
- Wait conditions

### 4. Run or Edit

- **Run immediately** - Execute the test
- **Edit steps** - Refine if needed
- **Save** - Store for later use

## 💡 Best Practices

### Be Specific

❌ **Bad:**
```
Test login
```

✅ **Good:**
```
Test user login with valid credentials:
- Navigate to /login
- Enter email: test@example.com
- Enter password: password123
- Click "Sign In" button
- Verify redirect to /dashboard
- Verify welcome message appears
```

### Include Verification

❌ **Bad:**
```
Click submit button
```

✅ **Good:**
```
Click submit button
Verify success message "Form submitted successfully" appears
Verify form is cleared
```

### Describe Edge Cases

```
Test login with invalid credentials:
- Enter email: wrong@example.com
- Enter password: wrongpassword
- Click login
- Verify error message "Invalid credentials" appears
- Verify user stays on login page
```

## 🎯 Advanced Features

### Multi-Page Flows

```
Test complete user journey:
1. Registration:
   - Visit /signup
   - Fill registration form
   - Verify email sent

2. Email Verification:
   - Click verification link
   - Verify account activated

3. First Login:
   - Login with new credentials
   - Complete onboarding wizard
   - Verify dashboard access
```

### Dynamic Data

```
Test with dynamic data:
- Generate random email: user_{timestamp}@test.com
- Use random name: Test User {random}
- Create unique order ID
```

### Conditional Logic

```
Test conditional behavior:
- If user is logged in:
  - Show dashboard
- Else:
  - Redirect to login
```

## 🔧 Customization

### Selector Strategy

AI automatically chooses the best selectors:

1. **Accessible selectors** (preferred)
   - `role="button"`
   - `aria-label="Submit"`
   - `data-testid="login-btn"`

2. **Semantic selectors**
   - `button[type="submit"]`
   - `input[name="email"]`

3. **Text-based** (fallback)
   - `text="Sign In"`
   - `placeholder="Enter email"`

### Wait Strategies

AI adds appropriate waits:

```javascript
// Wait for navigation
await page.waitForURL('/dashboard');

// Wait for element
await page.waitForSelector('[data-testid="welcome"]');

// Wait for network
await page.waitForLoadState('networkidle');
```

## 📊 Examples by Use Case

### Authentication Tests

```
Test password reset flow:
- Click "Forgot Password"
- Enter email: user@example.com
- Click "Send Reset Link"
- Verify "Email sent" message
- Check email inbox
- Click reset link
- Enter new password
- Confirm password
- Click "Reset Password"
- Verify "Password updated" message
- Login with new password
```

### E-commerce Tests

```
Test product filtering:
- Navigate to /products
- Select category "Electronics"
- Set price range $100-$500
- Apply filters
- Verify filtered results
- Verify price range is correct
- Verify category matches
```

### Form Tests

```
Test multi-step form:
Step 1 - Personal Info:
- Enter first name
- Enter last name
- Enter email
- Click "Next"

Step 2 - Address:
- Enter street address
- Enter city
- Select state
- Enter zip code
- Click "Next"

Step 3 - Review:
- Verify all information
- Click "Submit"
- Verify confirmation
```

## 🎨 Tips & Tricks

### 1. Use Clear Language

```
✅ "Click the blue 'Submit' button in the footer"
❌ "Click that button"
```

### 2. Specify Exact Text

```
✅ "Verify message 'Registration successful!'"
❌ "Check for success message"
```

### 3. Include URLs

```
✅ "Navigate to https://example.com/login"
❌ "Go to login page"
```

### 4. Describe Visual Elements

```
✅ "Click the red 'Delete' button with trash icon"
❌ "Click delete"
```

## 🐛 Troubleshooting

### AI Generates Wrong Selectors

**Solution:** Be more specific about element attributes:
```
Click the submit button with class "btn-primary" and text "Save Changes"
```

### Test Steps Missing

**Solution:** Break down into smaller, explicit steps:
```
Instead of: "Complete checkout"
Use: 
- Click "Proceed to Checkout"
- Fill billing information
- Select payment method
- Click "Place Order"
```

### Assertions Not Working

**Solution:** Specify exact expected values:
```
Instead of: "Verify success"
Use: "Verify text 'Order #12345 confirmed' is visible"
```

## 📚 Related Pages

- [Test Execution](Test-Execution) - Run your tests
- [Self-Healing Tests](Self-Healing-Tests) - Auto-fix broken tests
- [Best Practices](Best-Practices) - Testing guidelines

---

[← Back to Home](Home) | [Next: Test Execution →](Test-Execution)
