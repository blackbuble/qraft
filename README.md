# QRAFT - AI-Powered Quality Intelligence Platform

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20)](https://laravel.com)
[![Node](https://img.shields.io/badge/Node-18%2B-339933)](https://nodejs.org)

QRAFT is a comprehensive SaaS platform for automated testing with AI-powered test generation, multi-tenancy, and subscription management.

## ✨ Features

### 🏢 Multi-Tenancy
- Organization-based isolation
- Team member management
- Role-based permissions (Owner/Admin/Member)
- Tenant switching

### 💳 Subscription Management
- Three-tier pricing (Free/Pro/Enterprise)
- Stripe integration
- Usage tracking and limits
- Billing portal access
- 14-day free trial

### 👥 Team Management
- Email-based invitations
- Role assignment
- Member management UI
- Plan limit enforcement

### 🔧 Super Admin Panel
- Platform-wide management
- Organization oversight
- User management
- MRR tracking and analytics

### 🤖 AI-Powered Testing
- Automated test generation
- Element discovery
- Self-healing selectors
- Flakiness detection

## 🚀 Quick Start

### Option 1: Laravel Herd (macOS) - Recommended ⭐

The fastest way to get started on Mac:

```bash
# 1. Install Herd from https://herd.laravel.com
# 2. Clone to Herd directory
cd ~/Herd
git clone https://github.com/yourusername/qraft.git
cd qraft

# 3. Run automated setup
npm run setup

# 4. Visit http://qraft.test
```

**✅ Herd includes:** PHP, Composer, MySQL, Node.js, automatic HTTPS

📖 [Full Herd Setup Guide](docs/SETUP_HERD.md)

---

### Option 2: Laragon (Windows) - Recommended ⭐

The fastest way to get started on Windows:

```bash
# 1. Install Laragon from https://laragon.org
# 2. Clone to Laragon directory
cd C:\laragon\www
git clone https://github.com/yourusername/qraft.git
cd qraft

# 3. Run automated setup
npm run setup

# 4. Visit http://qraft.test
```

**✅ Laragon includes:** PHP, Composer, MySQL, Node.js, Apache/Nginx

📖 [Full Laragon Setup Guide](docs/SETUP_LARAGON.md)

---

### Option 3: Manual Setup (Any OS)

If you prefer manual setup or have existing PHP/MySQL:

```bash
# Clone the repository
git clone https://github.com/yourusername/qraft.git
cd qraft

# Run automated setup
npm run setup
```

The setup script will:
- Copy `.env.example` to `.env`
- Install Composer dependencies
- Install NPM dependencies
- Generate application key
- Run migrations
- Seed demo data
- Build assets

📖 [Manual Setup Guide](DEVELOPMENT.md)

---

### Prerequisites (Manual Setup Only)

Herd and Laragon include everything. For manual setup you need:

- PHP 8.2 or higher
- Node.js 18 or higher
- Composer
- MySQL/PostgreSQL
- Stripe account (for billing)

### Configuration

1. **Database**: Update `.env` with your database credentials
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qraft
DB_USERNAME=root
DB_PASSWORD=
```

2. **Stripe**: Add your Stripe credentials
```env
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx
STRIPE_PRICE_PRO=price_xxx
STRIPE_PRICE_ENTERPRISE=price_yyy
```

3. **Google Gemini**: Add your API key
```env
GEMINI_API_KEY=your_api_key_here
```

### Development

```bash
# Start development servers (Laravel + Vite)
npm run dev

# Or start them separately
npm run dev:laravel  # Laravel server on :8000
npm run dev:vite     # Vite server on :5173
```

Visit: http://localhost:8000

## 📝 Available Scripts

All scripts are cross-platform compatible (Windows & Mac):

```bash
npm run dev          # Start both Laravel & Vite servers
npm run setup        # Initial project setup
npm run build        # Build production assets
npm run test         # Run PHPUnit tests
npm run db:reset     # Reset database with demo data
npm run db:seed      # Seed database
npm run migrate      # Run migrations
npm run format       # Format code with Pint
npm run lint         # Run code analysis
```

## 👤 Demo Accounts

After running `npm run setup` or `npm run db:reset`:

### Super Admin
- **Email**: admin@qraft.test
- **Password**: password
- **URL**: /super-admin

### Organization Owner (Pro Plan)
- **Email**: owner@qraft.test
- **Password**: password
- **Organization**: Acme Corporation

### Organization Member (Free Plan)
- **Email**: member@qraft.test
- **Password**: password
- **Organization**: Tech Startup

## 📊 Subscription Plans

### Free Tier
- 1 project
- 100 test runs/month
- 3 team members
- 10 AI generations/month
- 1 GB storage

### Pro Tier ($49/month)
- 10 projects
- 5,000 test runs/month
- 10 team members
- 500 AI generations/month
- 50 GB storage

### Enterprise Tier ($299/month)
- Unlimited projects
- Unlimited test runs
- Unlimited team members
- Unlimited AI generations
- Unlimited storage
- Priority support

## 🏗️ Architecture

### Tech Stack
- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Filament 3, Livewire, Alpine.js
- **Database**: MySQL/PostgreSQL
- **Queue**: Redis (optional)
- **Testing**: Playwright
- **AI**: Google Gemini API
- **Payments**: Stripe (Laravel Cashier)

### Key Components
- **Multi-Tenancy**: Filament native tenancy with organization isolation
- **Billing**: Laravel Cashier with Stripe
- **Admin Panel**: Separate Filament panel for super admins
- **Testing**: Playwright-based test execution
- **AI**: Gemini-powered test generation

## 🔒 Security

- Tenant data isolation via global scopes
- Role-based access control
- Stripe webhook verification
- Plan limit enforcement
- Super admin middleware protection

## 🧪 Testing

```bash
# Run all tests
npm run test

# Run specific test suite
npm run test:unit

# Run with coverage
php artisan test --coverage
```

## 📚 Documentation

- [SaaS Overview](docs/saas_overview.md)
- [Architecture Guide](docs/saas_architecture.md)
- [Quick Start Guide](docs/saas_guide.md)
- [Implementation Plan](docs/implementation_plan.md)

## 🤝 Contributing

Contributions are welcome! Please read our contributing guidelines first.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel & Filament teams
- Stripe for payment processing
- Google Gemini for AI capabilities
- Playwright for testing infrastructure

## 📞 Support

For support, email support@qraft.test or open an issue on GitHub.

---

**Built with ❤️ using Laravel & Filament**
