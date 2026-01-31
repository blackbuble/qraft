# Contributing to QRAFT

Thank you for your interest in contributing to QRAFT! This document provides guidelines and instructions for contributing to the project.

## 🎯 Ways to Contribute

- 🐛 **Report bugs** - Help us identify and fix issues
- 💡 **Suggest features** - Share ideas for improvements
- 📝 **Improve documentation** - Help others understand the project
- 🔧 **Submit code** - Fix bugs or implement features
- 🧪 **Write tests** - Improve code coverage
- 🎨 **Improve UI/UX** - Enhance user experience

## 🚀 Getting Started

### Prerequisites

- PHP 8.2+
- Node.js 18+
- Composer
- MySQL/PostgreSQL
- Git

**Recommended:**
- [Laravel Herd](https://herd.laravel.com) (macOS)
- [Laragon](https://laragon.org) (Windows)

### Setup Development Environment

1. **Fork the repository**
   ```bash
   # Click "Fork" on GitHub
   ```

2. **Clone your fork**
   ```bash
   git clone https://github.com/YOUR_USERNAME/qraft.git
   cd qraft
   ```

3. **Add upstream remote**
   ```bash
   git remote add upstream https://github.com/ORIGINAL_OWNER/qraft.git
   ```

4. **Run setup**
   ```bash
   npm run setup
   ```

5. **Start development**
   ```bash
   npm run dev
   ```

## 📋 Development Workflow

### 1. Create a Branch

Always create a new branch for your work:

```bash
git checkout main
git pull upstream main
git checkout -b feature/your-feature-name
```

**Branch naming conventions:**
- `feature/` - New features
- `fix/` - Bug fixes
- `docs/` - Documentation updates
- `refactor/` - Code refactoring
- `test/` - Test additions/updates
- `chore/` - Maintenance tasks

### 2. Make Your Changes

- Write clean, readable code
- Follow existing code style
- Add tests for new features
- Update documentation as needed

### 3. Test Your Changes

```bash
# Run tests
npm run test

# Format code
npm run format

# Run static analysis
npm run lint
```

### 4. Commit Your Changes

We follow [Conventional Commits](https://www.conventionalcommits.org/):

```bash
git add .
git commit -m "feat: add user profile settings"
```

**Commit types:**
- `feat:` - New feature
- `fix:` - Bug fix
- `docs:` - Documentation changes
- `style:` - Code style changes (formatting)
- `refactor:` - Code refactoring
- `test:` - Test updates
- `chore:` - Maintenance tasks

**Examples:**
```bash
git commit -m "feat: add team invitation system"
git commit -m "fix: resolve subscription webhook error"
git commit -m "docs: update setup instructions"
```

### 5. Push to Your Fork

```bash
git push origin feature/your-feature-name
```

### 6. Create a Pull Request

1. Go to your fork on GitHub
2. Click "New Pull Request"
3. Select your branch
4. Fill in the PR template
5. Submit for review

## 📝 Pull Request Guidelines

### PR Title

Use conventional commit format:
```
feat: add user profile settings
fix: resolve login redirect issue
docs: improve installation guide
```

### PR Description

Include:
- **What** - What changes were made
- **Why** - Why these changes are needed
- **How** - How the changes work
- **Testing** - How to test the changes
- **Screenshots** - For UI changes

**Template:**
```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
How to test these changes

## Screenshots (if applicable)
Add screenshots for UI changes

## Checklist
- [ ] Code follows project style
- [ ] Tests added/updated
- [ ] Documentation updated
- [ ] All tests passing
```

### Code Review Process

1. **Automated checks** - CI/CD runs tests
2. **Code review** - Maintainers review code
3. **Feedback** - Address review comments
4. **Approval** - Get approval from maintainers
5. **Merge** - Changes merged to main

## 💻 Code Style

### PHP

We use [Laravel Pint](https://laravel.com/docs/pint) for code formatting:

```bash
npm run format
```

**Guidelines:**
- Follow PSR-12 standards
- Use type hints
- Write docblocks for public methods
- Keep methods focused and small

**Example:**
```php
/**
 * Create a new organization.
 *
 * @param  array  $data
 * @return Organization
 */
public function createOrganization(array $data): Organization
{
    return Organization::create([
        'name' => $data['name'],
        'slug' => Str::slug($data['name']),
    ]);
}
```

### JavaScript

- Use ES6+ syntax
- Use `const` and `let` (not `var`)
- Use arrow functions
- Add comments for complex logic

### Blade Templates

- Use proper indentation
- Keep logic minimal
- Use components when possible

## 🧪 Testing

### Writing Tests

```bash
# Create a test
php artisan make:test OrganizationTest

# Run tests
npm run test

# Run specific test
php artisan test --filter=OrganizationTest
```

**Test structure:**
```php
public function test_user_can_create_organization(): void
{
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->post('/organizations', [
            'name' => 'Test Org',
        ]);
    
    $response->assertStatus(201);
    $this->assertDatabaseHas('organizations', [
        'name' => 'Test Org',
    ]);
}
```

### Test Coverage

- Aim for 80%+ coverage
- Test happy paths
- Test edge cases
- Test error handling

## 📚 Documentation

### Code Documentation

- Add docblocks to classes and methods
- Explain complex logic with comments
- Keep comments up-to-date

### User Documentation

Update relevant docs when:
- Adding new features
- Changing existing behavior
- Adding configuration options

**Documentation files:**
- `README.md` - Project overview
- `DEVELOPMENT.md` - Development guide
- `docs/` - Detailed guides

## 🐛 Bug Reports

### Before Reporting

1. Check existing issues
2. Try latest version
3. Verify it's reproducible

### Creating a Bug Report

Use the bug report template:

```markdown
## Bug Description
Clear description of the bug

## Steps to Reproduce
1. Go to '...'
2. Click on '...'
3. See error

## Expected Behavior
What should happen

## Actual Behavior
What actually happens

## Environment
- OS: macOS 13
- PHP: 8.2
- Laravel: 11
- Browser: Chrome 120

## Screenshots
Add screenshots if applicable

## Additional Context
Any other relevant information
```

## 💡 Feature Requests

### Creating a Feature Request

```markdown
## Feature Description
Clear description of the feature

## Problem it Solves
What problem does this solve?

## Proposed Solution
How should it work?

## Alternatives Considered
Other solutions you've thought about

## Additional Context
Any other relevant information
```

## 🔒 Security Issues

**DO NOT** create public issues for security vulnerabilities.

Instead:
1. Email security@qraft.test
2. Include detailed description
3. Wait for response before disclosure

## 📜 Code of Conduct

### Our Standards

- Be respectful and inclusive
- Accept constructive criticism
- Focus on what's best for the community
- Show empathy towards others

### Unacceptable Behavior

- Harassment or discrimination
- Trolling or insulting comments
- Personal or political attacks
- Publishing others' private information

## 🎓 Learning Resources

### Laravel
- [Laravel Documentation](https://laravel.com/docs)
- [Laracasts](https://laracasts.com)

### Filament
- [Filament Documentation](https://filamentphp.com/docs)
- [Filament Examples](https://filamentphp.com/community)

### Testing
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Testing](https://laravel.com/docs/testing)

## 🤝 Community

- **GitHub Discussions** - Ask questions, share ideas
- **Discord** - Real-time chat (coming soon)
- **Twitter** - Follow [@qraft](https://twitter.com/qraft)

## 📞 Getting Help

- **Documentation** - Check docs first
- **GitHub Issues** - Search existing issues
- **Discussions** - Ask the community
- **Email** - support@qraft.test

## ✅ Checklist for Contributors

Before submitting a PR:

- [ ] Code follows project style
- [ ] Tests added/updated
- [ ] All tests passing
- [ ] Documentation updated
- [ ] Commit messages follow conventions
- [ ] PR description is complete
- [ ] No merge conflicts
- [ ] Branch is up-to-date with main

## 🎉 Recognition

Contributors are recognized in:
- `CONTRIBUTORS.md` file
- Release notes
- Project README

Thank you for contributing to QRAFT! 🚀

---

**Questions?** Open a discussion or email contribute@qraft.test
