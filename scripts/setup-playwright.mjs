#!/usr/bin/env node

/**
 * Playwright setup script for QRAFT
 * Ensures Playwright browsers are installed on both Windows and Mac
 */

import { execSync } from 'child_process';
import { platform } from 'os';
import { existsSync } from 'fs';

const isWindows = platform() === 'win32';

console.log('🎭 Setting up Playwright...\n');

// Check if inspector-service exists
if (!existsSync('./inspector-service')) {
    console.error('❌ inspector-service directory not found');
    console.log('💡 Make sure you are in the project root directory');
    process.exit(1);
}

// Install Playwright browsers
console.log('📦 Installing Playwright browsers...');
console.log('   This may take a few minutes on first run\n');

try {
    // Navigate to inspector-service and install browsers
    const command = isWindows
        ? 'cd inspector-service && npx playwright install --with-deps'
        : 'cd inspector-service && npx playwright install --with-deps';

    execSync(command, {
        stdio: 'inherit',
        shell: true
    });

    console.log('\n✅ Playwright browsers installed successfully!');
    console.log('\n📝 Installed browsers:');
    console.log('   - Chromium');
    console.log('   - Firefox');
    console.log('   - WebKit');

} catch (err) {
    console.error('\n❌ Failed to install Playwright browsers');
    console.error(err.message);

    console.log('\n💡 Try manually:');
    console.log('   cd inspector-service');
    console.log('   npx playwright install --with-deps');

    process.exit(1);
}

// Verify installation
console.log('\n🔍 Verifying installation...');

try {
    const verifyCommand = isWindows
        ? 'cd inspector-service && npx playwright --version'
        : 'cd inspector-service && npx playwright --version';

    execSync(verifyCommand, { stdio: 'inherit', shell: true });

    console.log('\n✅ Playwright is ready to use!');

} catch (err) {
    console.warn('\n⚠️  Could not verify Playwright installation');
}

console.log('\n🎉 Setup complete!\n');
