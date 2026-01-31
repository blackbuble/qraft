#!/usr/bin/env node

/**
 * Cross-platform test runner for QRAFT
 * Runs PHPUnit tests on both Windows and macOS
 */

import { execSync } from 'child_process';
import { platform } from 'os';

const isWindows = platform() === 'win32';
const phpCommand = isWindows ? 'php.exe' : 'php';

console.log('🧪 Running tests...\n');

try {
    const vendorBin = isWindows ? '.\\vendor\\bin\\' : './vendor/bin/';
    execSync(`${vendorBin}phpunit`, { stdio: 'inherit' });
    console.log('\n✅ All tests passed!');
} catch (err) {
    console.error('\n❌ Some tests failed');
    process.exit(1);
}
