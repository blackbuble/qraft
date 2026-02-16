# Cross-Platform Development Scripts

This directory contains Node.js scripts that work on both Windows and macOS.

## Scripts

### `dev.mjs`
Starts both Laravel and Vite development servers simultaneously.

**Usage:**
```bash
npm run dev
```

**Features:**
- Auto-detects OS (Windows/Mac)
- Starts Laravel server on port 8000
- Starts Vite server on port 5173
- Graceful shutdown with Ctrl+C

---

### `setup.mjs`
Initial project setup script.

**Usage:**
```bash
npm run setup
```

**What it does:**
1. Creates `.env` file from `.env.example`
2. Installs Composer dependencies
3. Installs NPM dependencies
4. Generates application key
5. Runs database migrations
6. Seeds demo data
7. Builds assets

---

### `test.mjs`
Runs PHPUnit tests.

**Usage:**
```bash
npm run test
```

---

### `db-reset.mjs`
Resets database and seeds demo data with confirmation prompt.

**Usage:**
```bash
npm run db:reset
```

**Demo accounts created:**
- Super Admin: admin@qraft.test / password
- Org Owner: owner@qraft.test / password
- Org Member: member@qraft.test / password

---

## Why Node.js Scripts?

These scripts use Node.js instead of shell scripts for several reasons:

1. **Cross-Platform**: Works identically on Windows, macOS, and Linux
2. **No Bash Required**: Windows users don't need WSL or Git Bash
3. **Consistent Behavior**: Same output and error handling across all platforms
4. **Easy to Maintain**: JavaScript is familiar to most developers
5. **Built-in Tools**: Node.js provides excellent process management

## Platform Detection

All scripts automatically detect the operating system and use the correct commands:

- **Windows**: Uses `.exe` extensions and backslashes
- **macOS/Linux**: Uses standard Unix commands

## Error Handling

Each script includes proper error handling:
- Exits with code 1 on failure
- Shows helpful error messages
- Cleans up processes on termination
