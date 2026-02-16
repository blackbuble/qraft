# QRAFT Development Guide

## Getting Started

### Quick Setup (Recommended)

The fastest way to get started is using our automated setup script:

```bash
npm run setup
```

This will handle everything automatically on both Windows and macOS.

### Manual Setup

If you prefer manual setup:

1. **Install Dependencies**
```bash
composer install
npm install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup**
```bash
php artisan migrate
php artisan db:seed --class=SaasSeeder
```

4. **Build Assets**
```bash
npm run build
```

## Development Workflow

### Starting Development Servers

**Option 1: Both servers together (Recommended)**
```bash
npm run dev
```

This starts both Laravel (port 8000) and Vite (port 5173) servers.

**Option 2: Separate servers**
```bash
# Terminal 1
npm run dev:laravel

# Terminal 2
npm run dev:vite
```

### Running Tests

```bash
# All tests
npm run test

# Unit tests only
npm run test:unit

# With coverage
php artisan test --coverage
```

### Database Management

```bash
# Reset database with demo data
npm run db:reset

# Seed only
npm run db:seed

# Fresh migration
npm run migrate:fresh
```

### Code Quality

```bash
# Format code
npm run format

# Run static analysis
npm run lint
```

## Cross-Platform Compatibility

All npm scripts work identically on:
- ✅ Windows 10/11
- ✅ macOS (Intel & Apple Silicon)
- ✅ Linux

### How It Works

Our scripts use Node.js to detect the operating system and run the appropriate commands:

```javascript
// Automatically detects Windows vs Mac/Linux
const isWindows = platform() === 'win32';
const phpCommand = isWindows ? 'php.exe' : 'php';
```

## Common Issues

### Windows: PHP not found

Make sure PHP is in your PATH:
```powershell
php -v
```

If not found, add PHP to your system PATH or use Laravel Herd.

### Mac: Permission denied

Make scripts executable:
```bash
chmod +x scripts/*.mjs
```

### Database connection failed

1. Check your `.env` database credentials
2. Ensure MySQL/PostgreSQL is running
3. Create the database if it doesn't exist:
```sql
CREATE DATABASE qraft;
```

## Environment Variables

### Required

```env
APP_KEY=                    # Auto-generated
DB_CONNECTION=mysql
DB_DATABASE=qraft
```

### Optional (for full features)

```env
# Stripe (for billing)
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx

# Google Gemini (for AI features)
GEMINI_API_KEY=xxx

# Email (for invitations)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
```

## Project Structure

```
qraft/
├── app/
│   ├── Filament/
│   │   ├── Pages/          # Filament pages
│   │   ├── Resources/      # Tenant resources
│   │   ├── Widgets/        # Dashboard widgets
│   │   └── SuperAdmin/     # Super admin panel
│   ├── Models/             # Eloquent models
│   ├── Services/           # Business logic
│   └── Http/
│       ├── Controllers/    # HTTP controllers
│       └── Middleware/     # Custom middleware
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   └── views/             # Blade templates
├── scripts/               # Cross-platform scripts
│   ├── dev.mjs           # Development server
│   ├── setup.mjs         # Initial setup
│   ├── test.mjs          # Test runner
│   └── db-reset.mjs      # Database reset
└── tests/                # PHPUnit tests
```

## Troubleshooting

### Clear caches

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Rebuild everything

```bash
composer dump-autoload
npm run build
php artisan optimize
```

### Reset to fresh state

```bash
npm run db:reset
npm run build
```

## Tips & Tricks

### Use Laravel Herd (Recommended for Mac)

Laravel Herd provides PHP, Composer, and database out of the box:
- Download: https://herd.laravel.com

### Use Laragon (Recommended for Windows)

Laragon includes everything you need:
- Download: https://laragon.org

### Hot Module Replacement (HMR)

Vite provides instant updates during development. Just save your files and see changes immediately.

### Database GUI Tools

- **TablePlus**: https://tableplus.com (Mac/Windows)
- **DBeaver**: https://dbeaver.io (Free, cross-platform)
- **phpMyAdmin**: Web-based

## Next Steps

1. ✅ Run `npm run setup`
2. ✅ Configure `.env`
3. ✅ Run `npm run dev`
4. ✅ Visit http://localhost:8000
5. ✅ Login with demo accounts
6. 🚀 Start building!

## Getting Help

- 📖 [Laravel Docs](https://laravel.com/docs)
- 📖 [Filament Docs](https://filamentphp.com/docs)
- 💬 [GitHub Issues](https://github.com/yourusername/qraft/issues)
- 📧 Email: support@qraft.test
