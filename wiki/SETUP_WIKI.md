# GitHub Wiki Setup Guide

GitHub Wiki is a separate git repository. Follow these steps to set it up.

## 📋 Prerequisites

- GitHub repository created
- Wiki content in `wiki/` folder
- Git installed

## 🚀 Setup Steps

### Step 1: Enable Wiki on GitHub

1. Go to your GitHub repository: `https://github.com/blackbuble/qraft`
2. Click **Settings** tab
3. Scroll to **Features** section
4. Check ✅ **Wikis** checkbox
5. Click **Save**

### Step 2: Initialize Wiki

1. Go to **Wiki** tab in your repository
2. Click **Create the first page**
3. Enter any title (e.g., "Home")
4. Enter any content (will be replaced)
5. Click **Save Page**

This creates the wiki git repository.

### Step 3: Clone Wiki Repository

```bash
# Clone the wiki repository (separate from main repo)
git clone https://github.com/blackbuble/qraft.wiki.git

cd qraft.wiki
```

### Step 4: Copy Wiki Files

```bash
# Copy all wiki files from main repo
cp ../qraft/wiki/*.md .

# List files to verify
ls -la
```

You should see:
- Home.md
- Installation-Guide.md
- AI-Test-Generation.md
- Organizations.md
- FAQ.md

### Step 5: Push to Wiki Repository

```bash
# Add all files
git add .

# Commit
git commit -m "Initial wiki content

- Home page with navigation
- Installation guide
- AI test generation guide
- Organizations guide
- FAQ page"

# Push to wiki repository
git push origin master
```

### Step 6: Verify

1. Go to `https://github.com/blackbuble/qraft/wiki`
2. You should see all wiki pages
3. Click through pages to verify links work

## 🔧 Alternative: Manual Upload

If git clone doesn't work:

1. Go to Wiki tab on GitHub
2. Click **New Page** for each wiki file
3. Copy content from `wiki/*.md` files
4. Paste into GitHub wiki editor
5. Save each page

**Page names:**
- `Home` (from Home.md)
- `Installation-Guide` (from Installation-Guide.md)
- `AI-Test-Generation` (from AI-Test-Generation.md)
- `Organizations` (from Organizations.md)
- `FAQ` (from FAQ.md)

## 📝 Updating Wiki

### Via Git

```bash
cd qraft.wiki

# Make changes to .md files
vim Home.md

# Commit and push
git add .
git commit -m "Update wiki content"
git push origin master
```

### Via GitHub UI

1. Go to wiki page
2. Click **Edit**
3. Make changes
4. Click **Save Page**

## 🎯 Wiki Best Practices

### File Naming

- Use hyphens for spaces: `Installation-Guide.md`
- Use PascalCase: `AI-Test-Generation.md`
- Home page must be: `Home.md`

### Links

**Internal links:**
```markdown
[Installation Guide](Installation-Guide)
[FAQ](FAQ)
```

**External links:**
```markdown
[GitHub Repo](https://github.com/blackbuble/qraft)
```

### Images

Upload images to wiki:
```markdown
![Screenshot](images/screenshot.png)
```

Or use external URLs:
```markdown
![Logo](https://example.com/logo.png)
```

## 🐛 Troubleshooting

### Wiki tab not visible

**Solution:** Enable Wikis in repository Settings → Features

### Can't clone wiki repository

**Solution:** Create first page via GitHub UI first

### Links not working

**Solution:** Use exact page names without `.md` extension

### Changes not showing

**Solution:** 
- Clear browser cache
- Wait a few seconds for GitHub to update
- Check you pushed to correct repository

## 📚 Resources

- [GitHub Wiki Documentation](https://docs.github.com/en/communities/documenting-your-project-with-wikis)
- [Markdown Guide](https://guides.github.com/features/mastering-markdown/)

---

**Quick Commands:**

```bash
# Clone wiki
git clone https://github.com/blackbuble/qraft.wiki.git

# Copy files
cp ../qraft/wiki/*.md .

# Push changes
git add . && git commit -m "Update wiki" && git push
```
