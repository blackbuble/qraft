# QRAFT Setup with Laravel Herd (macOS)

Laravel Herd is the fastest way to get QRAFT running on macOS. It includes PHP, Composer, and database management out of the box.

## 🚀 Quick Start with Herd

### 1. Install Laravel Herd

Download and install from: https://herd.laravel.com

Herd includes:
- ✅ PHP 8.2+ (multiple versions)
- ✅ Composer
- ✅ MySQL/PostgreSQL
- ✅ Node.js
- ✅ Automatic HTTPS
- ✅ `.test` domain management

### 2. Clone QRAFT

```bash
cd ~/Herd  # Default Herd directory
git clone https://github.com/yourusername/qraft.git
cd qraft
```

### 3. Run Setup

```bash
npm run setup
```

Our setup script automatically detects Herd and:
- ✅ Installs dependencies
- ✅ Configures database (auto-detected)
- ✅ Runs migrations
- ✅ Seeds demo data
- ✅ Builds assets

### 4. Access Your Site

Herd automatically serves your site at:
```
http://qraft.test
```

No need to run `php artisan serve`! 🎉

## 🔧 Development Workflow

### Start Development

```bash
npm run dev
```

This will:
- ✅ Detect Herd (skip Laravel server)
- ✅ Start Vite for hot reload
- ✅ Show your Herd URL

### Database Management

Herd includes **DBngin** for database management:

1. Open Herd menu bar app
2. Click "Open DBngin"
3. MySQL is already running!

**Default credentials:**
- Host: `127.0.0.1`
- Port: `3306`
- Username: `root`
- Password: *(empty)*

### Switch PHP Versions

Herd supports multiple PHP versions:

1. Click Herd menu bar icon
2. Select "PHP Version"
3. Choose your version (8.2, 8.3, etc.)

QRAFT requires PHP 8.2+

## 📊 Herd-Specific Features

### Automatic HTTPS

Herd provides automatic HTTPS:
```
https://qraft.test
```

### Custom Domains

Add custom domains in Herd settings:
```
http://my-qraft.test
```

### Database GUI

Use TablePlus (recommended) or any MySQL client:
- Host: `127.0.0.1`
- Port: `3306`
- User: `root`
- Password: *(leave empty)*
- Database: `qraft`

## 🎯 Recommended Herd Settings

### PHP Configuration

1. Open Herd → Preferences
2. PHP tab:
   - Memory Limit: `512M` (for AI features)
   - Max Execution Time: `300` (for long tests)
   - Upload Max Filesize: `100M`

### Database

1. Use MySQL (default)
2. Create database via Herd:
   ```bash
   herd db create qraft
   ```

## 💡 Tips & Tricks

### Use Herd CLI

```bash
# Link current directory
herd link

# Unlink
herd unlink

# List all sites
herd sites

# Open site in browser
herd open

# Restart services
herd restart
```

### Environment Variables

Herd auto-configures `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qraft
DB_USERNAME=root
DB_PASSWORD=
```

### Queue Workers

Run queue workers with Herd:
```bash
herd queue:work
```

### Scheduler

Herd can run Laravel scheduler:
```bash
herd schedule:run
```

## 🐛 Troubleshooting

### Site not accessible

```bash
# Re-link the site
herd link

# Restart Herd
herd restart
```

### Database connection failed

1. Check DBngin is running
2. Verify database exists:
   ```bash
   herd db list
   ```
3. Create if missing:
   ```bash
   herd db create qraft
   ```

### PHP version issues

```bash
# Check current version
php -v

# Switch version in Herd menu
# Or via CLI:
herd php 8.2
```

## 🚀 Production Deployment

Herd is for development only. For production:

1. Use Laravel Forge: https://forge.laravel.com
2. Or deploy to:
   - DigitalOcean App Platform
   - AWS Elastic Beanstalk
   - Heroku
   - Ploi

## 📚 Resources

- Herd Docs: https://herd.laravel.com/docs
- Laravel Docs: https://laravel.com/docs
- QRAFT Docs: See `DEVELOPMENT.md`

## ✅ Checklist

- [ ] Install Laravel Herd
- [ ] Clone QRAFT to `~/Herd`
- [ ] Run `npm run setup`
- [ ] Visit `http://qraft.test`
- [ ] Login with demo accounts
- [ ] Start building! 🎉

---

**Herd makes Laravel development on Mac incredibly easy!**
