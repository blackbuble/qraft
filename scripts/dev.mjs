#!/usr/bin/env node

/**
 * Cross-platform development server script for QRAFT
 * Optimized for Laravel Herd (Mac) and Laragon (Windows)
 */

import { spawn } from 'child_process';
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

console.log('🚀 Starting QRAFT development environment...\n');

let laravelServer = null;

// Only start Laravel server if NOT using Herd/Laragon
if (isHerd) {
    console.log('✨ Laravel Herd detected - PHP server managed by Herd');
    console.log('📍 Your site: http://qraft.test (or your Herd domain)');
} else if (isLaragon) {
    console.log('✨ Laragon detected - PHP server managed by Laragon');
    console.log('📍 Your site: http://qraft.test (or your Laragon domain)');
} else {
    console.log('📦 Starting Laravel server...');
    const phpCommand = isWindows ? 'php.exe' : 'php';
    laravelServer = spawn(phpCommand, ['artisan', 'serve'], {
        stdio: 'inherit',
        shell: true,
        cwd: process.cwd()
    });
    console.log('📍 Laravel: http://localhost:8000');
}

// Always start Vite for hot reload
console.log('⚡ Starting Vite server...');
const viteServer = spawn('npm', ['run', 'dev:vite'], {
    stdio: 'inherit',
    shell: true,
    cwd: process.cwd()
});

// Handle process termination
const cleanup = () => {
    console.log('\n\n🛑 Shutting down servers...');
    if (laravelServer) {
        laravelServer.kill();
    }
    viteServer.kill();
    process.exit(0);
};

process.on('SIGINT', cleanup);
process.on('SIGTERM', cleanup);

// Handle errors
if (laravelServer) {
    laravelServer.on('error', (err) => {
        console.error('❌ Laravel server error:', err);
    });
}

viteServer.on('error', (err) => {
    console.error('❌ Vite server error:', err);
});

console.log('\n✅ Development servers started!');
console.log('📍 Vite: http://localhost:5173');
console.log('\n💡 Press Ctrl+C to stop all servers\n');

if (isHerd || isLaragon) {
    console.log('💡 Tip: Your PHP server is managed by ' + (isHerd ? 'Herd' : 'Laragon'));
    console.log('   Just save your files and refresh the browser!\n');
}
