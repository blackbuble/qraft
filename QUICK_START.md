# QRAFT Quick Reference

## 🚀 Setup Commands

### First Time Setup
```bash
npm run setup          # Automated setup (detects Herd/Laragon)
```

### Development
```bash
npm run dev            # Start all servers (auto-detects environment)
npm run dev:vite       # Vite only (if using Herd/Laragon)
npm run dev:laravel    # Laravel only (manual setup)
```

### Database
```bash
npm run db:reset       # Reset & seed database
npm run db:seed        # Seed only
npm run migrate        # Run migrations
npm run migrate:fresh  # Fresh migration
```

### Testing
```bash
npm run test           # Run all tests
npm run test:unit      # Unit tests only
```

### Code Quality
```bash
npm run format         # Format with Pint
npm run lint           # Static analysis
```

### Build
```bash
npm run build          # Production build
```

---

## 🌐 Access URLs

### Herd (macOS)
```
http://qraft.test
https://qraft.test  (automatic HTTPS)
```

### Laragon (Windows)
```
http://qraft.test
```

### Manual Setup
```
http://localhost:8000
```

### Vite Dev Server
```
http://localhost:5173
```

---

## 👤 Demo Accounts

### Super Admin
```
Email: admin@qraft.test
Password: password
URL: /super-admin
```

### Organization Owner (Pro Plan)
```
Email: owner@qraft.test
Password: password
Organization: Acme Corporation
```

### Organization Member (Free Plan)
```
Email: member@qraft.test
Password: password
Organization: Tech Startup
```

---

## 📊 Subscription Plans

### Free
- 1 project
- 100 test runs/month
- 3 team members
- 10 AI generations/month

### Pro ($49/month)
- 10 projects
- 5,000 test runs/month
- 10 team members
- 500 AI generations/month

### Enterprise ($299/month)
- Unlimited everything
- Priority support

---

## 🔧 Environment Detection

Our scripts automatically detect:

✅ **Laravel Herd** (macOS)
- Skips `php artisan serve`
- Uses Herd's PHP server
- Auto-configures database

✅ **Laragon** (Windows)
- Skips `php artisan serve`
- Uses Laragon's web server
- Auto-configures database

✅ **Manual Setup**
- Starts Laravel server
- Requires manual database config

---

## 🐛 Quick Troubleshooting

### Site not accessible
```bash
# Herd
herd restart

# Laragon
# Restart Laragon app

# Manual
npm run dev
```

### Database connection failed
```bash
# Check .env database credentials
# Ensure MySQL is running
# Create database if needed
```

### Clear caches
```bash
php artisan optimize:clear
```

### Reset everything
```bash
npm run db:reset
npm run build
```

---

## 📁 Important Files

```
.env                   # Environment config
package.json           # NPM scripts
composer.json          # PHP dependencies
database/seeders/      # Database seeders
scripts/               # Cross-platform scripts
```

---

## 🔗 Useful Links

- [Full Documentation](DEVELOPMENT.md)
- [Herd Setup](docs/SETUP_HERD.md)
- [Laragon Setup](docs/SETUP_LARAGON.md)
- [Laravel Docs](https://laravel.com/docs)
- [Filament Docs](https://filamentphp.com/docs)

---

## 💡 Pro Tips

### Herd Users
```bash
herd link              # Link current directory
herd open              # Open in browser
herd db create qraft   # Create database
```

### Laragon Users
- Right-click tray icon for quick menu
- Use built-in terminal (pre-configured)
- HeidiSQL for database management

### All Users
- Use `npm run` commands (cross-platform)
- Scripts auto-detect your environment
- No need to remember different commands!

---

**Quick Start: `npm run setup` → `npm run dev` → Visit site → Login → Build! 🚀**
