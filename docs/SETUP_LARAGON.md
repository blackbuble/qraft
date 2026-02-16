# QRAFT Setup with Laragon (Windows)

Laragon is the fastest way to get QRAFT running on Windows. It's a portable, isolated, and fast development environment.

## 🚀 Quick Start with Laragon

### 1. Install Laragon

Download and install from: https://laragon.org/download/

**Recommended:** Laragon Full (includes everything)

Laragon includes:
- ✅ PHP 8.2+ (multiple versions)
- ✅ Composer
- ✅ MySQL/PostgreSQL
- ✅ Node.js
- ✅ Apache/Nginx
- ✅ Auto virtual hosts
- ✅ Pretty URLs

### 2. Clone QRAFT

```bash
cd C:\laragon\www
git clone https://github.com/yourusername/qraft.git
cd qraft
```

### 3. Run Setup

```bash
npm run setup
```

Our setup script automatically detects Laragon and:
- ✅ Installs dependencies
- ✅ Configures database
- ✅ Runs migrations
- ✅ Seeds demo data
- ✅ Builds assets

### 4. Access Your Site

Laragon automatically creates a virtual host:
```
http://qraft.test
```

No need to run `php artisan serve`! 🎉

## 🔧 Development Workflow

### Start Laragon

1. Open Laragon
2. Click "Start All"
3. Services start automatically:
   - ✅ Apache/Nginx
   - ✅ MySQL
   - ✅ PHP

### Start Development

```bash
npm run dev
```

This will:
- ✅ Detect Laragon (skip Laravel server)
- ✅ Start Vite for hot reload
- ✅ Show your Laragon URL

### Database Management

Laragon includes **HeidiSQL** for database management:

1. Right-click Laragon tray icon
2. Select "MySQL" → "HeidiSQL"
3. Connect automatically!

**Default credentials:**
- Host: `127.0.0.1`
- Port: `3306`
- Username: `root`
- Password: *(empty)*

## 📊 Laragon-Specific Features

### Quick App Creation

Right-click Laragon → Quick app → Laravel

But for QRAFT, use our setup script instead.

### Pretty URLs

Laragon automatically creates:
```
http://qraft.test
```

### Terminal

Laragon includes a powerful terminal:
- Right-click Laragon → Terminal
- Pre-configured with PHP, Composer, Node

### Switch PHP Versions

1. Right-click Laragon tray icon
2. PHP → Version
3. Select version (8.2, 8.3, etc.)

QRAFT requires PHP 8.2+

## 🎯 Recommended Laragon Settings

### PHP Configuration

1. Right-click Laragon → PHP → php.ini
2. Update these settings:
   ```ini
   memory_limit = 512M
   max_execution_time = 300
   upload_max_filesize = 100M
   post_max_size = 100M
   ```
3. Restart Laragon

### Web Server

**Apache (Default):**
- Good for compatibility
- `.htaccess` support

**Nginx (Recommended):**
- Faster performance
- Better for Laravel
- Switch: Right-click → Nginx → Enable

### Database

1. Use MySQL (default)
2. Create database via HeidiSQL or terminal:
   ```bash
   mysql -u root -e "CREATE DATABASE qraft"
   ```

## 💡 Tips & Tricks

### Use Laragon Terminal

Right-click Laragon → Terminal

Pre-configured with:
- PHP
- Composer
- Node.js
- Git

### Quick Menu

Right-click Laragon for quick access:
- Start/Stop services
- Open project folder
- Open database manager
- Access configuration files

### Environment Variables

Laragon auto-configures `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qraft
DB_USERNAME=root
DB_PASSWORD=
```

### SSL/HTTPS

Enable HTTPS:
1. Right-click Laragon
2. Apache/Nginx → SSL → qraft
3. Access: `https://qraft.test`

### Mail Catcher

Laragon includes MailHog:
1. Right-click Laragon → Tools → MailHog
2. Update `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=127.0.0.1
   MAIL_PORT=1025
   ```

## 🐛 Troubleshooting

### Site not accessible

1. Check Laragon is running (green icon)
2. Restart Laragon
3. Check virtual host:
   - Right-click → Apache/Nginx → sites-enabled
   - Should see `qraft.test.conf`

### Database connection failed

1. Check MySQL is running (green in Laragon)
2. Verify database exists in HeidiSQL
3. Create if missing:
   ```bash
   mysql -u root -e "CREATE DATABASE qraft"
   ```

### Port conflicts

If port 80 is in use:
1. Right-click Laragon → Preferences
2. Change Apache port to 8080
3. Access: `http://qraft.test:8080`

### PHP version issues

```bash
# Check version in Laragon terminal
php -v

# Switch version:
# Right-click Laragon → PHP → Version
```

## 🚀 Production Deployment

Laragon is for development only. For production:

1. Use Laravel Forge: https://forge.laravel.com
2. Or deploy to:
   - DigitalOcean App Platform
   - AWS Elastic Beanstalk
   - Azure App Service
   - Ploi

## 📚 Resources

- Laragon Docs: https://laragon.org/docs/
- Laravel Docs: https://laravel.com/docs
- QRAFT Docs: See `DEVELOPMENT.md`

## ✅ Checklist

- [ ] Install Laragon Full
- [ ] Start Laragon (green icon)
- [ ] Clone QRAFT to `C:\laragon\www`
- [ ] Run `npm run setup`
- [ ] Visit `http://qraft.test`
- [ ] Login with demo accounts
- [ ] Start building! 🎉

## 🎯 Laragon vs XAMPP/WAMP

**Why Laragon?**

✅ **Faster**: Optimized for performance
✅ **Isolated**: Each project separate
✅ **Modern**: Latest PHP, Node.js
✅ **Auto Virtual Hosts**: No manual config
✅ **Portable**: Can run from USB
✅ **Pretty URLs**: Automatic `.test` domains
✅ **Built-in Tools**: Terminal, mail catcher, etc.

---

**Laragon makes Laravel development on Windows incredibly easy!**
