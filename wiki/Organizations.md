# Organizations

Learn about QRAFT's multi-tenant organization system.

## 🏢 Overview

QRAFT uses a multi-tenant architecture where each organization has:
- Isolated data and resources
- Own subscription plan
- Team members with roles
- Usage tracking and limits

## 🎯 Key Concepts

### Organization

An organization represents a company or team using QRAFT.

**Properties:**
- Name
- Slug (unique identifier)
- Subscription plan (Free/Pro/Enterprise)
- Owner
- Team members
- Projects and tests

### Roles

**Owner:**
- Full control over organization
- Manage subscription
- Invite/remove members
- Delete organization

**Admin:**
- Manage projects and tests
- Invite members
- View analytics

**Member:**
- Create and run tests
- View assigned projects
- Limited permissions

## 🚀 Creating an Organization

### During Registration

New users automatically create an organization:

1. Sign up at `/register`
2. Enter organization name
3. Organization is created
4. User becomes owner

### From Dashboard

Existing users can create additional organizations:

1. Click organization switcher
2. Click "New Organization"
3. Enter organization details
4. Click "Create"

## 👥 Team Management

### Inviting Members

**As Owner/Admin:**

1. Go to "Team Members"
2. Click "Invite Member"
3. Enter email address
4. Select role (Admin/Member)
5. Click "Send Invitation"

**Invitation Process:**
- Email sent to invitee
- Link valid for 7 days
- User accepts invitation
- User joins organization

### Managing Members

**View Members:**
- See all team members
- View roles and status
- See join date

**Change Roles:**
- Click member row
- Select new role
- Save changes

**Remove Members:**
- Click member row
- Click "Remove"
- Confirm removal

## 💳 Subscription Plans

### Free Plan

**Limits:**
- 5 projects
- 100 test runs/month
- 2 team members
- Basic support

**Best for:**
- Small teams
- Personal projects
- Evaluation

### Pro Plan ($49/month)

**Limits:**
- 50 projects
- 5,000 test runs/month
- 10 team members
- Priority support

**Features:**
- AI test generation
- Self-healing tests
- Advanced analytics
- Email notifications

**Best for:**
- Growing teams
- Production use
- Multiple projects

### Enterprise Plan (Custom)

**Unlimited:**
- Projects
- Test runs
- Team members

**Features:**
- Everything in Pro
- SSO/SAML
- Custom branding
- Dedicated support
- SLA guarantee

**Best for:**
- Large organizations
- Enterprise requirements
- High volume

## 📊 Usage Tracking

### View Usage

Dashboard shows:
- Projects used / limit
- Test runs this month / limit
- Team members / limit
- Storage used

### Usage Alerts

Notifications when:
- 80% of limit reached
- 100% of limit reached
- Limit exceeded

### Upgrade Prompts

When limits reached:
- Upgrade suggestion
- Plan comparison
- One-click upgrade

## 🔧 Organization Settings

### General Settings

- Organization name
- Slug (URL identifier)
- Logo/avatar
- Timezone
- Date format

### Billing Settings

- Current plan
- Payment method
- Billing history
- Invoices
- Upgrade/downgrade

### Danger Zone

- Transfer ownership
- Delete organization
- Export data

## 🔐 Data Isolation

### How It Works

Each organization's data is completely isolated:

**Database Level:**
- All models scoped to organization
- Queries automatically filtered
- No cross-organization access

**File Storage:**
- Separate directories per organization
- Screenshots and artifacts isolated

**API Access:**
- Organization ID in all requests
- Middleware enforces isolation

### Security

- Row-level security
- Tenant verification on every request
- No shared resources between organizations

## 📈 Organization Analytics

### Dashboard Metrics

**Test Statistics:**
- Total test runs
- Pass/fail rate
- Average duration
- Flakiness score

**Team Activity:**
- Active members
- Tests created
- Recent activity

**Usage Trends:**
- Daily/weekly/monthly charts
- Comparison to limits
- Growth metrics

## 🔄 Organization Lifecycle

### Creation

1. User signs up
2. Organization created
3. Owner assigned
4. Free plan activated
5. Welcome email sent

### Growth

1. Invite team members
2. Create projects
3. Run tests
4. Monitor usage
5. Upgrade when needed

### Deletion

1. Owner initiates deletion
2. Confirmation required
3. 30-day grace period
4. Data exported
5. Organization deleted

**Note:** Deleted organizations can be restored within 30 days.

## 💡 Best Practices

### Naming

✅ **Good:**
- "Acme Corp"
- "Tech Startup Inc"
- "QA Team"

❌ **Bad:**
- "test"
- "my org"
- "123"

### Team Structure

**Small Team (2-5 people):**
- 1 Owner
- All others as Members

**Medium Team (6-20 people):**
- 1 Owner
- 2-3 Admins
- Rest as Members

**Large Team (20+ people):**
- 1 Owner
- Multiple Admins per department
- Members organized by project

### Plan Selection

**Start with Free:**
- Evaluate QRAFT
- Learn the platform
- Small projects

**Upgrade to Pro:**
- Production use
- Growing team
- Multiple projects

**Enterprise:**
- Large organization
- Custom requirements
- High volume

## 🐛 Troubleshooting

### Can't Create Organization

**Issue:** "Organization limit reached"

**Solution:** Free users limited to 1 organization. Upgrade or delete existing organization.

### Can't Invite Members

**Issue:** "Member limit reached"

**Solution:** Upgrade plan or remove inactive members.

### Can't Delete Organization

**Issue:** "Active subscription"

**Solution:** Cancel subscription first, then delete.

## 📚 Related Pages

- [Team Management](Team-Management) - Detailed team features
- [Subscription Plans](Subscription-Plans) - Plan details
- [Billing & Payments](Billing-And-Payments) - Payment management

---

[← Back to Home](Home) | [Next: Team Management →](Team-Management)
