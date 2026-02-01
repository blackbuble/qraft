# QRAFT Development Guide

Complete guide for developing QRAFT locally.

## 📋 Table of Contents

- [Prerequisites](#prerequisites)
- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [Project Structure](#project-structure)
- [Database Management](#database-management)
- [Testing](#testing)
- [Code Quality](#code-quality)
- [Common Tasks](#common-tasks)
- [Troubleshooting](#troubleshooting)
- [Best Practices](#best-practices)

---

## 📦 Prerequisites

### Required Software

- **PHP 8.2+** with extensions:
  - BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- **Composer 2.5+**
- **Node.js 18+** and npm
- **MySQL 8.0+** or **PostgreSQL 13+**
- **Git**

### Recommended Tools

**For macOS:**
- [Laravel Herd](https://herd.laravel.com) - All-in-one PHP environment
- [TablePlus](https://tableplus.com) - Database GUI

**For Windows:**
- [Laragon](https://laragon.org) - Complete development environment
- [HeidiSQL](https://www.heidisql.com) - Database management

**For All Platforms:**
- [VS Code](https://code.visualstudio.com) with extensions:
  - PHP Intelephense
  - Laravel Blade Snippets
  - Tailwind CSS IntelliSense
  - ESLint
  - Prettier

---

## 🚀 Getting Started

### 1. Clone Repository

```bash
git clone https://github.com/blackbuble/qraft.git
cd qraft
```

### 2. Quick Setup

```bash
# One command to set everything up
npm run setup
```

This will:
- Copy `.env.example` to `.env`
- Install Composer dependencies
- Install NPM dependencies
- Generate application key
- Run migrations
- Seed demo data
- Build assets

### 3. Manual Setup (Alternative)

```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed --class=SaasSeeder

# Build assets
npm run build
```

---

## 💻 Development Workflow

### Starting Development

**With Herd or Laragon:**
```bash
# Only start Vite (PHP server handled automatically)
npm run dev
```

**Manual Setup:**
```bash
# Start both servers
npm run dev

# Or separately:
npm run dev:laravel  # Terminal 1
npm run dev:vite     # Terminal 2
```

### Access URLs

**Herd/Laragon:**
- Main app: `http://qraft.test`
- Super admin: `http://qraft.test/super-admin`

**Manual:**
- Main app: `http://localhost:8000`
- Super admin: `http://localhost:8000/super-admin`

### Demo Accounts

```
Super Admin:
- Email: admin@qraft.test
- Password: password

Organization Owner (Pro Plan):
- Email: owner@qraft.test
- Password: password

Organization Member (Free Plan):
- Email: member@qraft.test
- Password: password
```

---

## 📁 Project Structure

```
qraft/
├── app/
│   ├── Filament/              # Filament admin panels
│   │   ├── Pages/            # Custom pages
│   │   ├── Resources/        # CRUD resources
│   │   ├── Widgets/          # Dashboard widgets
│   │   └── SuperAdmin/       # Super admin panel
│   ├── Http/
│   │   ├── Controllers/      # HTTP controllers
│   │   └── Middleware/       # Custom middleware
│   ├── Models/               # Eloquent models
│   │   └── Concerns/         # Model traits
│   ├── Services/             # Business logic
│   └── Providers/            # Service providers
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── views/               # Blade templates
│   └── css/                 # Stylesheets
├── routes/
│   ├── web.php             # Web routes
│   └── api.php             # API routes
├── scripts/                # Cross-platform scripts
├── tests/                  # PHPUnit tests
└── docs/                   # Documentation
```

### Key Directories

**Filament Resources:**
- `app/Filament/Resources/` - Tenant resources (Projects, Tests, etc.)
- `app/Filament/SuperAdmin/Resources/` - Platform admin resources

**Models:**
- `app/Models/` - Eloquent models
- `app/Models/Concerns/BelongsToOrganization.php` - Tenant scoping trait

**Services:**
- `app/Services/PlanLimits.php` - Subscription limit enforcement
- `app/Services/UsageTracking.php` - Usage metrics tracking

---

## 🗄️ Database Management

### Migrations

```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (WARNING: destroys data)
php artisan migrate:fresh

# Check migration status
php artisan migrate:status
```

### Seeding

```bash
# Seed demo data
php artisan db:seed --class=SaasSeeder

# Reset database with demo data
npm run db:reset
```

### Tinker (Database REPL)

```bash
php artisan tinker

# Examples:
>>> \App\Models\User::count()
>>> \App\Models\Organization::with('users')->first()
>>> \App\Models\User::find(1)->organizations
```

---

## 🧪 Testing

### Running Tests

```bash
# All tests
npm run test

# Specific test file
php artisan test --filter=OrganizationTest

# With coverage
php artisan test --coverage

# Parallel execution
php artisan test --parallel
```

### Writing Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;

class OrganizationTest extends TestCase
{
    public function test_user_can_create_organization(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post('/organizations', [
                'name' => 'Test Organization',
            ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('organizations', [
            'name' => 'Test Organization',
        ]);
    }
}
```

### Test Database

Tests use a separate database. Configure in `.env.testing`:

```env
DB_DATABASE=qraft_testing
```

---

## ✨ Code Quality

### Code Formatting

```bash
# Format code with Laravel Pint
npm run format

# Check formatting
./vendor/bin/pint --test
```

### Static Analysis

```bash
# Run PHPStan/Larastan
npm run lint

# Or directly
./vendor/bin/phpstan analyse
```

### Code Style

We follow **PSR-12** coding standards:

```php
<?php

namespace App\Services;

class ExampleService
{
    public function __construct(
        private readonly UserRepository $users,
    ) {
    }

    public function processUser(int $userId): User
    {
        $user = $this->users->find($userId);
        
        if (! $user) {
            throw new UserNotFoundException();
        }
        
        return $user;
    }
}
```

---

## 🔧 Common Tasks

### Creating a New Resource

```bash
# Create Filament resource
php artisan make:filament-resource Project

# With pages
php artisan make:filament-resource Project --generate
```

### Creating a Migration

```bash
# Create migration
php artisan make:migration create_projects_table

# Create migration for existing table
php artisan make:migration add_status_to_projects_table
```

### Creating a Model

```bash
# Create model with migration, factory, and seeder
php artisan make:model Project -mfs

# With controller
php artisan make:model Project -mfsc
```

### Queue Workers

```bash
# Run queue worker
php artisan queue:work

# With specific queue
php artisan queue:work --queue=high,default

# Process one job
php artisan queue:work --once
```

### Cache Management

```bash
# Clear all caches
php artisan optimize:clear

# Individual caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan optimize
```

---

## 🐛 Troubleshooting

### Common Issues

#### 1. "Class not found" errors

```bash
composer dump-autoload
php artisan optimize:clear
```

#### 2. Database connection failed

```bash
# Check MySQL is running
mysql -u root -p

# Verify .env credentials
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qraft
DB_USERNAME=root
DB_PASSWORD=
```

#### 3. Permission errors (Linux/macOS)

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 4. NPM install fails

```bash
# Clear cache
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

#### 5. Vite not connecting

Check `vite.config.js`:
```js
export default defineConfig({
    server: {
        host: 'localhost',
        hmr: {
            host: 'localhost',
        },
    },
});
```

### Debug Mode

Enable debug mode in `.env`:
```env
APP_DEBUG=true
APP_ENV=local
```

View logs:
```bash
tail -f storage/logs/laravel.log
```

---

## 💡 Best Practices

### Code Organization

**Use Services for Business Logic:**
```php
// ❌ Bad: Logic in controller
public function store(Request $request)
{
    $user = User::create($request->all());
    $organization = Organization::create([...]);
    $organization->users()->attach($user);
    // ...
}

// ✅ Good: Logic in service
public function store(Request $request)
{
    return $this->organizationService->create($request->validated());
}
```

**Use Form Requests:**
```php
// ❌ Bad: Validation in controller
public function store(Request $request)
{
    $validated = $request->validate([...]);
}

// ✅ Good: Dedicated form request
public function store(StoreOrganizationRequest $request)
{
    return $this->service->create($request->validated());
}
```

### Database

**Use Transactions:**
```php
DB::transaction(function () use ($data) {
    $organization = Organization::create($data);
    $organization->users()->attach($userId);
    $organization->subscription()->create([...]);
});
```

**Eager Loading:**
```php
// ❌ Bad: N+1 queries
$organizations = Organization::all();
foreach ($organizations as $org) {
    echo $org->owner->name; // Query per iteration
}

// ✅ Good: Eager load
$organizations = Organization::with('owner')->get();
foreach ($organizations as $org) {
    echo $org->owner->name; // No extra queries
}
```

### Security

**Always Validate Input:**
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
    ]);
}
```

**Use Mass Assignment Protection:**
```php
class User extends Model
{
    protected $fillable = ['name', 'email'];
    protected $guarded = ['is_admin'];
}
```

---

## 🔄 Git Workflow

### Branch Naming

```bash
feature/add-user-profile
fix/login-redirect-issue
docs/update-readme
refactor/extract-service
test/add-unit-tests
chore/update-dependencies
```

### Commit Messages

Follow [Conventional Commits](https://www.conventionalcommits.org/):

```bash
feat: add team invitation system
fix: resolve subscription webhook error
docs: update installation guide
style: format code with Pint
refactor: extract organization service
test: add organization tests
chore: update dependencies
```

### Before Committing

```bash
# Format code
npm run format

# Run tests
npm run test

# Check for errors
npm run lint
```

---

## 📚 Additional Resources

### Laravel

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel News](https://laravel-news.com)
- [Laracasts](https://laracasts.com)

### Filament

- [Filament Documentation](https://filamentphp.com/docs)
- [Filament Examples](https://filamentphp.com/community)
- [Filament Plugins](https://filamentphp.com/plugins)

### Testing

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Testing](https://laravel.com/docs/testing)
- [Pest PHP](https://pestphp.com)

---

## 🤝 Getting Help

- 📖 [Documentation](docs/)
- 🐛 [Report Issues](https://github.com/blackbuble/qraft/issues)
- 💬 [Discussions](https://github.com/blackbuble/qraft/discussions)
- 📧 Email: dev@qraft.test

---

## 📝 Quick Reference

```bash
# Setup
npm run setup          # Initial setup
npm run dev            # Start development

# Database
npm run db:reset       # Reset database
npm run migrate        # Run migrations

# Testing
npm run test           # Run tests
npm run format         # Format code
npm run lint           # Static analysis

# Build
npm run build          # Production build
```

---

**Happy coding! 🚀**
