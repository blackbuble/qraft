# QRAFT Installation Guide

Complete installation guide for QRAFT on all platforms.

## 📋 Table of Contents

- [System Requirements](#system-requirements)
- [Installation Methods](#installation-methods)
  - [Option 1: Laravel Herd (macOS)](#option-1-laravel-herd-macos-recommended)
  - [Option 2: Laragon (Windows)](#option-2-laragon-windows-recommended)
  - [Option 3: Manual Installation](#option-3-manual-installation)
- [Post-Installation](#post-installation)
- [Configuration](#configuration)
- [Verification](#verification)
- [Troubleshooting](#troubleshooting)

---

## 📦 System Requirements

### Minimum Requirements

- **PHP**: 8.2 or higher
- **Node.js**: 18.0 or higher
- **Composer**: 2.5 or higher
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Memory**: 2GB RAM minimum
- **Disk Space**: 500MB free space

### Recommended Requirements

- **PHP**: 8.3
- **Node.js**: 20 LTS
- **Memory**: 4GB RAM
- **Disk Space**: 2GB free space

### PHP Extensions Required

```
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PCRE
- PDO
- Tokenizer
- XML
```

---

## 🚀 Installation Methods

Choose the method that best fits your platform and experience level.

---

## Option 1: Laravel Herd (macOS) ⭐ Recommended

**Best for:** macOS users who want the fastest setup

### Step 1: Install Laravel Herd

1. Download Herd from [herd.laravel.com](https://herd.laravel.com)
2. Open the downloaded `.dmg` file
3. Drag Herd to Applications
4. Launch Herd from Applications

**What Herd includes:**
- ✅ PHP 8.2, 8.3 (switchable)
- ✅ Composer
- ✅ MySQL via DBngin
- ✅ Node.js
- ✅ Automatic `.test` domains
- ✅ Automatic HTTPS

### Step 2: Clone QRAFT

```bash
# Navigate to Herd directory
cd ~/Herd

# Clone the repository
git clone https://github.com/yourusername/qraft.git
cd qraft
```

### Step 3: Run Setup

```bash
npm run setup
```

The setup script will:
- ✅ Install Composer dependencies
- ✅ Install NPM dependencies
- ✅ Create `.env` file
- ✅ Generate application key
- ✅ Run migrations
- ✅ Seed demo data
- ✅ Build assets

### Step 4: Access Your Site

Herd automatically serves your site at:
```
http://qraft.test
https://qraft.test (automatic HTTPS)
```

### Step 5: Start Development

```bash
npm run dev
```

This starts Vite for hot module replacement. Herd handles the PHP server automatically!

**✅ Installation Complete!**

📖 **Next:** See [docs/SETUP_HERD.md](docs/SETUP_HERD.md) for advanced Herd configuration

---

## Option 2: Laragon (Windows) ⭐ Recommended

**Best for:** Windows users who want the fastest setup

### Step 1: Install Laragon

1. Download Laragon Full from [laragon.org](https://laragon.org/download/)
2. Run the installer
3. Choose installation directory (default: `C:\laragon`)
4. Complete installation
5. Launch Laragon

**What Laragon includes:**
- ✅ PHP 8.2, 8.3 (switchable)
- ✅ Composer
- ✅ MySQL
- ✅ Node.js
- ✅ Apache/Nginx
- ✅ Automatic virtual hosts

### Step 2: Start Laragon

1. Click "Start All" in Laragon
2. Wait for services to start (green icons)

### Step 3: Clone QRAFT

```bash
# Open Laragon Terminal (right-click Laragon → Terminal)
cd C:\laragon\www

# Clone the repository
git clone https://github.com/yourusername/qraft.git
cd qraft
```

### Step 4: Run Setup

```bash
npm run setup
```

The setup script will:
- ✅ Install Composer dependencies
- ✅ Install NPM dependencies
- ✅ Create `.env` file
- ✅ Generate application key
- ✅ Run migrations
- ✅ Seed demo data
- ✅ Build assets

### Step 5: Access Your Site

Laragon automatically creates a virtual host at:
```
http://qraft.test
```

### Step 6: Start Development

```bash
npm run dev
```

This starts Vite for hot module replacement. Laragon handles the PHP server automatically!

**✅ Installation Complete!**

📖 **Next:** See [docs/SETUP_LARAGON.md](docs/SETUP_LARAGON.md) for advanced Laragon configuration

---

## Option 3: Manual Installation

**Best for:** Users with existing PHP/MySQL setup or Linux users

### Step 1: Install Prerequisites

#### macOS (via Homebrew)

```bash
# Install Homebrew if not installed
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install PHP
brew install php@8.3

# Install Composer
brew install composer

# Install Node.js
brew install node@20

# Install MySQL
brew install mysql
brew services start mysql
```

#### Ubuntu/Debian

```bash
# Update package list
sudo apt update

# Install PHP and extensions
sudo apt install php8.3 php8.3-cli php8.3-fpm php8.3-mysql \
  php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip \
  php8.3-bcmath php8.3-gd

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install MySQL
sudo apt install mysql-server
sudo systemctl start mysql
```

#### Windows (Manual)

1. **PHP**: Download from [windows.php.net](https://windows.php.net/download/)
2. **Composer**: Download from [getcomposer.org](https://getcomposer.org/download/)
3. **Node.js**: Download from [nodejs.org](https://nodejs.org/)
4. **MySQL**: Download from [dev.mysql.com](https://dev.mysql.com/downloads/installer/)

### Step 2: Clone Repository

```bash
git clone https://github.com/yourusername/qraft.git
cd qraft
```

### Step 3: Install Dependencies

```bash
# Install Composer dependencies
composer install

# Install NPM dependencies
npm install
```

### Step 4: Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 5: Configure Database

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qraft
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create database:

```bash
# MySQL
mysql -u root -p
CREATE DATABASE qraft;
EXIT;
```

### Step 6: Run Migrations

```bash
php artisan migrate
```

### Step 7: Seed Demo Data

```bash
php artisan db:seed --class=SaasSeeder
```

### Step 8: Build Assets

```bash
npm run build
```

### Step 9: Start Development Servers

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev:vite
```

### Step 10: Access Your Site

```
http://localhost:8000
```

**✅ Installation Complete!**

---

## 🔧 Post-Installation

### 1. Verify Installation

```bash
# Check PHP version
php -v

# Check Composer
composer --version

# Check Node.js
node -v

# Check NPM
npm -v
```

### 2. Test Application

Visit your site and login with demo accounts:

**Super Admin:**
- Email: `admin@qraft.test`
- Password: `password`
- URL: `/super-admin`

**Organization Owner:**
- Email: `owner@qraft.test`
- Password: `password`

**Organization Member:**
- Email: `member@qraft.test`
- Password: `password`

### 3. Run Tests

```bash
npm run test
```

All tests should pass ✅

---

## ⚙️ Configuration

### Required Configuration

#### 1. Application Settings

```env
APP_NAME=QRAFT
APP_ENV=local
APP_DEBUG=true
APP_URL=http://qraft.test
```

#### 2. Database Settings

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qraft
DB_USERNAME=root
DB_PASSWORD=
```

### Optional Configuration

#### 1. Stripe (for billing features)

```env
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx
STRIPE_PRICE_PRO=price_xxx
STRIPE_PRICE_ENTERPRISE=price_xxx
```

Get your keys from [stripe.com/dashboard](https://dashboard.stripe.com/)

#### 2. Google Gemini (for AI features)

```env
GEMINI_API_KEY=your_api_key_here
```

Get your key from [ai.google.dev](https://ai.google.dev/)

#### 3. Email (for invitations)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@qraft.test
MAIL_FROM_NAME="${APP_NAME}"
```

#### 4. Queue (optional, for background jobs)

```env
QUEUE_CONNECTION=database
```

For production, use Redis:
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

## ✅ Verification

### Check Installation

```bash
# 1. Check database connection
php artisan migrate:status

# 2. Check if demo data exists
php artisan tinker
>>> \App\Models\User::count()
>>> exit

# 3. Check if assets are built
ls -la public/build

# 4. Test application
php artisan test
```

### Expected Results

- ✅ Migrations: All migrated
- ✅ Users: 3+ users exist
- ✅ Assets: `public/build` directory exists
- ✅ Tests: All passing

---

## 🐛 Troubleshooting

### Common Issues

#### 1. "Class not found" errors

```bash
composer dump-autoload
php artisan optimize:clear
```

#### 2. Database connection failed

- Check MySQL is running
- Verify credentials in `.env`
- Ensure database exists

```bash
# Create database if missing
mysql -u root -p
CREATE DATABASE qraft;
```

#### 3. Permission errors (Linux/macOS)

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 4. NPM install fails

```bash
# Clear npm cache
npm cache clean --force

# Delete node_modules
rm -rf node_modules package-lock.json

# Reinstall
npm install
```

#### 5. Port already in use

```bash
# Use different port
php artisan serve --port=8001
```

#### 6. Vite not connecting

Check `vite.config.js` has correct server settings:
```js
server: {
    host: 'localhost',
}
```

### Getting Help

- 📖 Check [DEVELOPMENT.md](DEVELOPMENT.md)
- 🐛 Search [GitHub Issues](https://github.com/yourusername/qraft/issues)
- 💬 Ask in [Discussions](https://github.com/yourusername/qraft/discussions)
- 📧 Email: support@qraft.test

---

## 🚀 Next Steps

After installation:

1. **Read the docs**
   - [QUICK_START.md](QUICK_START.md) - Quick reference
   - [DEVELOPMENT.md](DEVELOPMENT.md) - Development guide
   - [CONTRIBUTING.md](CONTRIBUTING.md) - How to contribute

2. **Explore features**
   - Login with demo accounts
   - Create a test project
   - Try team invitations
   - Check subscription management

3. **Configure for production**
   - Set up Stripe account
   - Configure email service
   - Set up queue workers
   - Enable caching

4. **Start developing**
   - Read [CONTRIBUTING.md](CONTRIBUTING.md)
   - Check [GitHub Issues](https://github.com/yourusername/qraft/issues)
   - Join the community

---

## 📊 Installation Comparison

| Feature | Herd (macOS) | Laragon (Windows) | Manual |
|---------|--------------|-------------------|--------|
| **Setup Time** | 5 minutes | 10 minutes | 30+ minutes |
| **Difficulty** | ⭐ Easy | ⭐ Easy | ⭐⭐⭐ Advanced |
| **Auto PHP** | ✅ Yes | ✅ Yes | ❌ No |
| **Auto Database** | ✅ Yes | ✅ Yes | ❌ No |
| **Auto Domains** | ✅ Yes | ✅ Yes | ❌ No |
| **HTTPS** | ✅ Auto | ⚙️ Manual | ⚙️ Manual |
| **Version Switch** | ✅ Easy | ✅ Easy | ⚙️ Manual |
| **Recommended** | ✅ macOS | ✅ Windows | ✅ Linux |

---

## 📝 Summary

**Fastest Setup:**
- **macOS**: Use Laravel Herd
- **Windows**: Use Laragon
- **Linux**: Manual installation

**Commands to remember:**
```bash
npm run setup    # Initial setup
npm run dev      # Start development
npm run test     # Run tests
npm run db:reset # Reset database
```

**Demo accounts:**
- admin@qraft.test / password (Super Admin)
- owner@qraft.test / password (Org Owner)
- member@qraft.test / password (Org Member)

---

**Need help?** Check [TROUBLESHOOTING.md](TROUBLESHOOTING.md) or open an issue!

**Ready to contribute?** Read [CONTRIBUTING.md](CONTRIBUTING.md)!

**Happy coding! 🎉**
