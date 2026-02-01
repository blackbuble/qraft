# Installation Guide

Complete guide to installing QRAFT on all platforms.

## 📋 System Requirements

### Minimum Requirements
- PHP 8.2+
- Node.js 18+
- Composer 2.5+
- MySQL 8.0+ or PostgreSQL 13+
- 2GB RAM
- 500MB disk space

### Recommended Requirements
- PHP 8.3
- Node.js 20 LTS
- 4GB RAM
- 2GB disk space

## 🚀 Installation Methods

### Option 1: Laravel Herd (macOS) ⭐ Recommended

**Step 1: Install Herd**
1. Download from [herd.laravel.com](https://herd.laravel.com)
2. Install and launch Herd

**Step 2: Clone QRAFT**
```bash
cd ~/Herd
git clone https://github.com/blackbuble/qraft.git
cd qraft
```

**Step 3: Run Setup**
```bash
npm run setup
```

**Step 4: Access**
- Visit: `http://qraft.test`
- Login: `admin@qraft.test` / `password`

### Option 2: Laragon (Windows) ⭐ Recommended

**Step 1: Install Laragon**
1. Download from [laragon.org](https://laragon.org)
2. Install and start Laragon

**Step 2: Clone QRAFT**
```bash
cd C:\laragon\www
git clone https://github.com/blackbuble/qraft.git
cd qraft
```

**Step 3: Run Setup**
```bash
npm run setup
```

**Step 4: Access**
- Visit: `http://qraft.test`
- Login: `admin@qraft.test` / `password`

### Option 3: Manual Installation

**Step 1: Install Prerequisites**

**macOS:**
```bash
brew install php@8.3 composer node mysql
```

**Ubuntu/Debian:**
```bash
sudo apt install php8.3 php8.3-cli php8.3-mysql composer nodejs mysql-server
```

**Step 2: Clone and Setup**
```bash
git clone https://github.com/blackbuble/qraft.git
cd qraft
npm run setup
```

**Step 3: Start Servers**
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

**Step 4: Access**
- Visit: `http://localhost:8000`

## ✅ Verification

After installation, verify everything works:

```bash
# Check PHP version
php -v

# Check database connection
php artisan migrate:status

# Run tests
php artisan test
```

## 🔐 Demo Accounts

```
Super Admin:
- Email: admin@qraft.test
- Password: password
- Access: /super-admin

Organization Owner (Pro Plan):
- Email: owner@qraft.test
- Password: password

Organization Member (Free Plan):
- Email: member@qraft.test
- Password: password
```

## 🐛 Troubleshooting

See [Common Issues](Common-Issues) for solutions.

## 📚 Next Steps

- [Quick Start](Quick-Start) - Learn the basics
- [Configuration](Configuration) - Configure your installation
- [First Steps](First-Steps) - Create your first test

---

[← Back to Home](Home) | [Next: Quick Start →](Quick-Start)
