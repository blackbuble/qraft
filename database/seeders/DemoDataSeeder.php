<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\TestScenario;
use App\Models\Run;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create demo user
        $user = User::firstOrCreate(
            ['email' => 'demo@qraft.dev'],
            [
                'name' => 'Demo User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Creating demo projects...');

        // Project 1: E-commerce Platform
        $this->createEcommerceProject($user);

        // Project 2: SaaS Dashboard
        $this->createSaaSProject($user);

        // Project 3: Blog Platform
        $this->createBlogProject($user);

        $this->command->info('✅ Demo data seeded successfully!');
        $this->command->info('');
        $this->command->info('📦 Created:');
        $this->command->info('  - 3 Sample Projects');
        $this->command->info('  - 9 Test Scenarios');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Email: demo@qraft.dev');
        $this->command->info('Password: password');
    }

    private function createEcommerceProject(User $user): void
    {
        $project = Project::create([
            'name' => '🛒 E-commerce Platform',
            'description' => 'Online shopping platform with product catalog, cart, and checkout. Base URL: https://demo-shop.example.com',
            'repo_url' => 'https://github.com/example/ecommerce',
            'status' => 'active',
        ]);

        // Scenario 1: User Registration
        $registration = TestScenario::create([
            'project_id' => $project->id,
            'title' => 'User Registration Flow',
            'description' => 'Test that new users can successfully register an account',
            'priority' => 'high',
            'frequency' => 'daily',
            'steps' => [
                ['action' => 'visit', 'value' => '/register', 'description' => 'Navigate to registration page'],
                ['action' => 'type', 'selector' => '#name', 'value' => 'John Doe', 'description' => 'Enter full name'],
                ['action' => 'type', 'selector' => '#email', 'value' => 'john@example.com', 'description' => 'Enter email address'],
                ['action' => 'type', 'selector' => '#password', 'value' => 'SecurePass123!', 'description' => 'Enter password'],
                ['action' => 'type', 'selector' => '#password_confirmation', 'value' => 'SecurePass123!', 'description' => 'Confirm password'],
                ['action' => 'click', 'selector' => 'button[type="submit"]', 'description' => 'Submit registration form'],
                ['action' => 'assert_visible', 'selector' => '.welcome-message', 'description' => 'Verify welcome message appears'],
                ['action' => 'assert_text', 'selector' => '.user-name', 'value' => 'John Doe', 'description' => 'Verify user name is displayed'],
            ],
        ]);
    }

    private function createSaaSProject(User $user): void
    {
        $project = Project::create([
            'name' => '📊 SaaS Dashboard',
            'description' => 'Analytics dashboard with user management and reporting. Base URL: https://app.example.com',
            'repo_url' => 'https://github.com/example/saas-dashboard',
            'status' => 'active',
        ]);

        // Scenario 1: Login Flow
        $login = TestScenario::create([
            'project_id' => $project->id,
            'title' => 'User Login with Valid Credentials',
            'description' => 'Test that users can login and access dashboard',
            'priority' => 'critical',
            'frequency' => 'hourly',
            'steps' => [
                ['action' => 'visit', 'value' => '/login', 'description' => 'Navigate to login page'],
                ['action' => 'type', 'selector' => '#email', 'value' => 'admin@example.com', 'description' => 'Enter email'],
                ['action' => 'type', 'selector' => '#password', 'value' => 'password123', 'description' => 'Enter password'],
                ['action' => 'click', 'selector' => 'button[type="submit"]', 'description' => 'Submit login form'],
                ['action' => 'assert_visible', 'selector' => '.dashboard', 'description' => 'Verify dashboard is visible'],
                ['action' => 'assert_text', 'selector' => '.welcome-text', 'value' => 'Welcome back', 'description' => 'Verify welcome message'],
            ],
        ]);
    }

    private function createBlogProject(User $user): void
    {
        $project = Project::create([
            'name' => '📝 Blog Platform',
            'description' => 'Content management system for publishing articles. Base URL: https://blog.example.com',
            'repo_url' => 'https://github.com/example/blog-platform',
            'status' => 'active',
        ]);

        // Scenario 1: Create New Post
        $createPost = TestScenario::create([
            'project_id' => $project->id,
            'title' => 'Create and Publish Blog Post',
            'description' => 'Test creating a new blog post with rich content',
            'priority' => 'high',
            'frequency' => 'daily',
            'steps' => [
                ['action' => 'visit', 'value' => '/admin/posts/create', 'description' => 'Navigate to create post page'],
                ['action' => 'type', 'selector' => '#title', 'value' => 'Getting Started with QRAFT', 'description' => 'Enter post title'],
                ['action' => 'type', 'selector' => '#slug', 'value' => 'getting-started-with-qraft', 'description' => 'Enter URL slug'],
                ['action' => 'click', 'selector' => '.editor-toolbar .bold', 'description' => 'Click bold button'],
                ['action' => 'type', 'selector' => '.editor-content', 'value' => 'QRAFT is an AI-powered testing platform...', 'description' => 'Enter post content'],
                ['action' => 'click', 'selector' => '#category-tutorial', 'description' => 'Select tutorial category'],
                ['action' => 'click', 'selector' => 'button.publish', 'description' => 'Publish post'],
                ['action' => 'assert_visible', 'selector' => '.published-badge', 'description' => 'Verify published badge'],
                ['action' => 'assert_text', 'selector' => '.post-title', 'value' => 'Getting Started with QRAFT', 'description' => 'Verify post title'],
            ],
        ]);
    }
}
