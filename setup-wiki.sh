#!/bin/bash

# GitHub Wiki Setup Script for QRAFT
# This script helps push wiki content to GitHub Wiki repository

set -e

echo "🔧 QRAFT GitHub Wiki Setup"
echo "=========================="
echo ""

# Check if we're in the right directory
if [ ! -d "wiki" ]; then
    echo "❌ Error: wiki/ folder not found"
    echo "💡 Please run this script from the qraft project root"
    exit 1
fi

# Configuration
WIKI_REPO="https://github.com/blackbuble/qraft.wiki.git"
TEMP_DIR="../qraft-wiki-temp"

echo "📋 Prerequisites Check:"
echo "  ✅ Wiki folder found"
echo ""

# Ask for confirmation
read -p "Have you enabled Wiki in GitHub Settings and created the first page? (y/n) " -n 1 -r
echo ""
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo ""
    echo "⏸️  Please complete these steps first:"
    echo "  1. Go to https://github.com/blackbuble/qraft/settings"
    echo "  2. Enable 'Wikis' in Features section"
    echo "  3. Go to Wiki tab and create first page"
    echo "  4. Run this script again"
    exit 1
fi

echo ""
echo "🔄 Cloning wiki repository..."

# Remove temp directory if exists
if [ -d "$TEMP_DIR" ]; then
    echo "  Removing existing temp directory..."
    rm -rf "$TEMP_DIR"
fi

# Clone wiki repository
if git clone "$WIKI_REPO" "$TEMP_DIR"; then
    echo "  ✅ Wiki repository cloned"
else
    echo "  ❌ Failed to clone wiki repository"
    echo ""
    echo "💡 Possible issues:"
    echo "  - Wiki not enabled in GitHub Settings"
    echo "  - No initial page created in Wiki"
    echo "  - Network connection issue"
    echo ""
    echo "Please ensure you've completed all prerequisites"
    exit 1
fi

echo ""
echo "📝 Copying wiki files..."

# Copy all markdown files from wiki folder
cp wiki/*.md "$TEMP_DIR/"

# List copied files
echo "  Files copied:"
cd "$TEMP_DIR"
for file in *.md; do
    echo "    - $file"
done

echo ""
echo "📤 Pushing to GitHub Wiki..."

# Git operations
git add .

if git diff --staged --quiet; then
    echo "  ℹ️  No changes to commit (wiki already up to date)"
else
    git commit -m "Update wiki content

- Home page with navigation
- Installation guide  
- AI test generation guide
- Organizations guide
- FAQ page
- Setup guide"
    
    git push origin master
    echo "  ✅ Wiki content pushed successfully"
fi

# Cleanup
cd ..
rm -rf "$TEMP_DIR"

echo ""
echo "✅ Wiki setup complete!"
echo ""
echo "🌐 View your wiki at:"
echo "   https://github.com/blackbuble/qraft/wiki"
echo ""
echo "📚 Wiki pages available:"
echo "   - Home"
echo "   - Installation Guide"
echo "   - AI Test Generation"
echo "   - Organizations"
echo "   - FAQ"
echo "   - Setup Wiki"
echo ""
