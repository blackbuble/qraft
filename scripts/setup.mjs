#!/usr/bin/env node

/**
 * Cross-platform development setup script for QRAFT
 * Optimized for Laravel Herd (Mac) and Laragon (Windows)
 * Includes Playwright browser installation
 */

import { execSync } from 'child_process';
import { platform, homedir } from 'os';
import { existsSync } from 'fs';
import { join } from 'path';

const isWindows = platform() === 'win32';

// Detect Herd (Mac)
const herdPath = join(homedir(), 'Library', 'Application Support', 'Herd');
const isHerd = !isWindows && existsSync(herdPath);

// Detect Laragon (Windows)
const laragonPaths = ['C:\\laragon', 'D:\\laragon'];
const isLaragon = isWindows && laragonPaths.some(path => existsSync(path));

// Determine commands based on environment
const phpCommand = isWindows ? 'php.exe' : 'php';
const composerCommand = isWindows ? 'composer.bat' : 'composer';

console.log('🔧 Setting up QRAFT...\n');

// Show environment info
if (isHerd) {
    console.log('✨ Detected Laravel Herd - Using optimized settings');
} else if (isLaragon) {
    console.log('✨ Detected Laragon - Using optimized settings');
} else {
    console.log('📦 Using standard PHP installation');
}
console.log('');

// Check if .env exists
if (!existsSync('.env')) {
    console.log('📝 Creating .env file...');
    try {
        if (isWindows) {
            execSync('copy .env.example .env', { stdio: 'inherit' });
        } else {
            execSync('cp .env.example .env', { stdio: 'inherit' });
        }
    } catch (err) {
        console.error('❌ Failed to create .env file');
        process.exit(1);
    }
}

// Install Composer dependencies
console.log('\n📦 Installing Composer dependencies...');
try {
    execSync(`${composerCommand} install`, { stdio: 'inherit' });
} catch (err) {
    console.error('❌ Composer install failed');
    console.log('\n💡 Tip: Make sure Composer is installed and in your PATH');
    if (isWindows && !isLaragon) {
        console.log('   Download: https://getcomposer.org/download/');
    }
    process.exit(1);
}

// Install NPM dependencies
console.log('\n📦 Installing NPM dependencies...');
try {
    execSync('npm install', { stdio: 'inherit' });
} catch (err) {
    console.error('❌ NPM install failed');
    process.exit(1);
}

// Setup Playwright browsers
console.log('\n🎭 Setting up Playwright browsers...');
console.log('   This may take a few minutes on first run...');
try {
    if (existsSync('./inspector-service')) {
        // Install inspector-service dependencies
        console.log('   Installing inspector-service dependencies...');
        execSync('cd inspector-service && npm install', { stdio: 'inherit', shell: true });

        // Install Playwright browsers
        console.log('   Installing Playwright browsers (Chromium, Firefox, WebKit)...');
        execSync('cd inspector-service && npx playwright install --with-deps', {
            stdio: 'inherit',
            shell: true
        });

        console.log('✅ Playwright browsers installed successfully');
    } else {
        console.warn('⚠️  inspector-service not found, skipping Playwright setup');
    }
} catch (err) {
    console.warn('⚠️  Playwright setup failed - you may need to run it manually');
    console.log('   Run: cd inspector-service && npx playwright install --with-deps');
}

// Generate application key
console.log('\n🔑 Generating application key...');
try {
    execSync(`${phpCommand} artisan key:generate`, { stdio: 'inherit' });
} catch (err) {
    console.error('❌ Key generation failed');
}

// For Herd/Laragon, database is usually ready
if (isHerd || isLaragon) {
    console.log('\n🗄️  Running database migrations...');
    try {
        execSync(`${phpCommand} artisan migrate`, { stdio: 'inherit' });
    } catch (err) {
        console.warn('⚠️  Migration failed - checking database configuration...');
        console.log('\n💡 For Herd: Database should be auto-configured');
        console.log('💡 For Laragon: Make sure MySQL is started in Laragon');
    }

    // Seed database
    console.log('\n🌱 Seeding database with demo data...');
    try {
        execSync(`${phpCommand} artisan db:seed --class=SaasSeeder`, { stdio: 'inherit' });
    } catch (err) {
        console.warn('⚠️  Seeding failed - database may already be seeded');
    }
} else {
    console.log('\n⏭️  Skipping database setup - configure .env first');
}

// Build assets
console.log('\n🎨 Building assets...');
try {
    execSync('npm run build', { stdio: 'inherit' });
} catch (err) {
    console.warn('⚠️  Asset build failed');
}

console.log('\n✅ Setup complete!\n');

// Environment-specific next steps
if (isHerd) {
    console.log('🎉 Laravel Herd Detected!');
    console.log('\n📚 Next steps:');
    console.log('  1. Your site is already served by Herd');
    console.log('  2. Visit: http://qraft.test (or your Herd domain)');
    console.log('  3. Run: npm run dev:vite (for Vite hot reload)');
    console.log('\n💡 Herd handles PHP server automatically!');
} else if (isLaragon) {
    console.log('🎉 Laragon Detected!');
    console.log('\n📚 Next steps:');
    console.log('  1. Make sure Laragon is running');
    console.log('  2. Visit: http://qraft.test (or your Laragon domain)');
    console.log('  3. Run: npm run dev:vite (for Vite hot reload)');
    console.log('\n💡 Laragon handles PHP server automatically!');
} else {
    console.log('📚 Next steps:');
    console.log('  1. Configure your .env file with database credentials');
    console.log('  2. Run: npm run dev');
    console.log('  3. Visit: http://localhost:8000');
}

console.log('\n🔐 Demo accounts:');
console.log('  Super Admin: admin@qraft.test / password');
console.log('  Org Owner: owner@qraft.test / password');
console.log('  Org Member: member@qraft.test / password');
console.log('\n🎉 Happy coding!\n');
