# Frequently Asked Questions (FAQ)

Common questions about QRAFT.

## 🚀 Getting Started

### What is QRAFT?

QRAFT is an AI-powered Quality Intelligence Platform that helps teams create, run, and maintain automated tests using natural language and artificial intelligence.

### Do I need coding skills?

No! QRAFT's AI test generation allows you to describe tests in plain English. The AI converts your descriptions into executable tests.

### What browsers are supported?

QRAFT uses Playwright and supports:
- Chromium (Chrome, Edge)
- Firefox
- WebKit (Safari)

### Is there a free plan?

Yes! The Free plan includes:
- 5 projects
- 100 test runs/month
- 2 team members

## 💻 Technical Questions

### What technologies does QRAFT use?

**Backend:**
- Laravel 11
- Filament 3
- MySQL/PostgreSQL

**Frontend:**
- Vite
- Tailwind CSS
- Alpine.js

**Testing:**
- Playwright
- Google Gemini AI

### Can I self-host QRAFT?

Yes! QRAFT is open-source and can be self-hosted. See [Installation Guide](Installation-Guide).

### What are the server requirements?

**Minimum:**
- PHP 8.2+
- 2GB RAM
- MySQL 8.0+

**Recommended:**
- PHP 8.3
- 4GB RAM
- Redis for queues

### Does QRAFT support CI/CD?

Yes! QRAFT can integrate with:
- GitHub Actions
- GitLab CI
- Jenkins
- CircleCI
- Any CI/CD via API

## 🤖 AI Features

### How does AI test generation work?

1. You describe test in natural language
2. Google Gemini AI analyzes description
3. AI generates Playwright test steps
4. You review and run the test

### How accurate is the AI?

AI accuracy depends on description clarity:
- **Clear descriptions:** 90%+ accuracy
- **Vague descriptions:** May need refinement

### Can I edit AI-generated tests?

Yes! All generated tests can be edited manually.

### What if AI generates wrong steps?

You can:
- Edit steps manually
- Regenerate with clearer description
- Provide feedback to improve AI

## 🏢 Organizations & Teams

### How many organizations can I create?

- **Free users:** 1 organization
- **Pro users:** Unlimited organizations

### Can I be in multiple organizations?

Yes! You can be a member of multiple organizations simultaneously.

### How do I switch between organizations?

Click the organization switcher in the top navigation bar.

### Can I transfer organization ownership?

Yes! Go to Organization Settings → Danger Zone → Transfer Ownership.

## 💳 Billing & Subscriptions

### How does billing work?

- Monthly or annual billing
- Automatic renewal
- Prorated upgrades/downgrades

### What payment methods are accepted?

- Credit/debit cards (via Stripe)
- PayPal
- Bank transfer (Enterprise only)

### Can I cancel anytime?

Yes! Cancel anytime from Billing settings. Access continues until period end.

### What happens if I exceed limits?

- **Test runs:** Tests blocked until next month or upgrade
- **Projects:** Can't create new projects
- **Team members:** Can't invite new members

### Is there a refund policy?

- 14-day money-back guarantee
- No refunds for partial months
- Annual plans prorated on cancellation

## 🔒 Security & Privacy

### Is my data secure?

Yes! Security measures include:
- Data encryption at rest and in transit
- Regular security audits
- SOC 2 Type II compliance (Enterprise)
- GDPR compliant

### Who can access my tests?

Only your organization members. Data is completely isolated between organizations.

### Can QRAFT access my application?

QRAFT only accesses URLs you provide for testing. No access to source code or databases.

### How is data backed up?

- Daily automated backups
- 30-day retention
- Point-in-time recovery

## 🧪 Testing

### What types of tests can I create?

- UI/E2E tests
- Form validation
- Authentication flows
- API tests
- Visual regression tests

### How long do tests take to run?

Depends on test complexity:
- Simple tests: 10-30 seconds
- Complex flows: 1-5 minutes
- Full suites: 5-30 minutes

### Can I run tests in parallel?

Yes! Pro and Enterprise plans support parallel execution.

### How do I handle flaky tests?

QRAFT's Flakiness Intelligence:
- Detects flaky tests
- Analyzes failure patterns
- Suggests fixes
- Auto-retry with smart logic

## 📊 Reporting & Analytics

### What metrics are tracked?

- Test pass/fail rates
- Execution duration
- Flakiness scores
- Team activity
- Usage statistics

### Can I export reports?

Yes! Export as:
- PDF
- CSV
- Excel
- JSON (API)

### How long is data retained?

- **Free:** 30 days
- **Pro:** 90 days
- **Enterprise:** Unlimited

## 🔧 Troubleshooting

### Tests are failing randomly

This is test flakiness. Check:
- Network conditions
- Wait times
- Element selectors
- Flakiness Intelligence dashboard

### Playwright browsers not installing

Run manually:
```bash
cd inspector-service
npx playwright install --with-deps
```

### Database connection errors

Check `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=qraft
DB_USERNAME=root
DB_PASSWORD=
```

### Vite not connecting

Check `vite.config.js` server settings and ensure port 5173 is available.

## 🆘 Support

### How do I get help?

**Free Plan:**
- Community support
- Documentation
- GitHub Discussions

**Pro Plan:**
- Email support (24-48h response)
- Priority bug fixes

**Enterprise:**
- Dedicated support
- Phone support
- Custom SLA

### Where can I report bugs?

[GitHub Issues](https://github.com/blackbuble/qraft/issues)

### How do I request features?

[GitHub Discussions](https://github.com/blackbuble/qraft/discussions)

### Is there a community?

Yes!
- GitHub Discussions
- Discord (coming soon)
- Twitter: @qraft

## 📚 Learning Resources

### Where can I learn more?

- [Documentation](https://github.com/blackbuble/qraft/tree/main/docs)
- [Wiki](Home)
- [Video Tutorials](https://youtube.com/@qraft) (coming soon)
- [Blog](https://blog.qraft.test) (coming soon)

### Are there tutorials?

Yes! Check:
- [Quick Start](Quick-Start)
- [First Steps](First-Steps)
- [AI Test Generation](AI-Test-Generation)

### Can I get certified?

Certification program coming Q3 2026!

---

**Still have questions?**

- 📧 Email: support@qraft.test
- 💬 [Discussions](https://github.com/blackbuble/qraft/discussions)
- 🐛 [Report Issue](https://github.com/blackbuble/qraft/issues)

---

[← Back to Home](Home)
