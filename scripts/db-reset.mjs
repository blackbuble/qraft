#!/usr/bin/env node

/**
 * Cross-platform database reset script
 * Works on both Windows and macOS
 */

import { execSync } from 'child_process';
import { platform } from 'os';
import readline from 'readline';

const isWindows = platform() === 'win32';
const phpCommand = isWindows ? 'php.exe' : 'php';

const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

console.log('⚠️  This will reset your database and seed it with demo data.\n');

rl.question('Are you sure you want to continue? (yes/no): ', (answer) => {
    if (answer.toLowerCase() === 'yes' || answer.toLowerCase() === 'y') {
        console.log('\n🗄️  Resetting database...');

        try {
            execSync(`${phpCommand} artisan migrate:fresh --seed --seeder=SaasSeeder`, {
                stdio: 'inherit'
            });

            console.log('\n✅ Database reset complete!');
            console.log('\n📝 Demo accounts:');
            console.log('  Super Admin: admin@qraft.test / password');
            console.log('  Org Owner: owner@qraft.test / password');
            console.log('  Org Member: member@qraft.test / password\n');
        } catch (err) {
            console.error('\n❌ Database reset failed');
            process.exit(1);
        }
    } else {
        console.log('\n❌ Database reset cancelled');
    }

    rl.close();
});
