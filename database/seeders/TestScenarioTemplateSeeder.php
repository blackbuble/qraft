<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\TestScenario;
use Illuminate\Database\Seeder;

class TestScenarioTemplateSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get first project or create a template project
        $project = Project::first();

        if (!$project) {
            $project = Project::create([
                'name' => 'Template Project',
                'description' => 'Pre-built test scenario templates',
                'base_url' => 'https://example.com',
            ]);
        }

        $templates = [
            [
                'title' => '🔐 Login Flow Test',
                'description' => 'Complete user login journey with email and password validation',
                'priority' => 'high',
                'steps' => [
                    [
                        'action' => 'visit',
                        'value' => '/login',
                        'description' => 'Navigate to login page'
                    ],
                    [
                        'action' => 'assert_visible',
                        'selector' => 'form',
                        'selector_type' => 'css',
                        'description' => 'Verify login form is visible'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#email',
                        'selector_type' => 'css',
                        'value' => 'test@example.com',
                        'description' => 'Enter email address'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#password',
                        'selector_type' => 'css',
                        'value' => 'password123',
                        'description' => 'Enter password'
                    ],
                    [
                        'action' => 'click',
                        'selector' => 'button[type="submit"]',
                        'selector_type' => 'css',
                        'description' => 'Click login button'
                    ],
                    [
                        'action' => 'wait',
                        'value' => '2000',
                        'description' => 'Wait for redirect'
                    ],
                    [
                        'action' => 'assert_url',
                        'value' => '/dashboard',
                        'description' => 'Verify redirected to dashboard'
                    ],
                    [
                        'action' => 'assert_visible',
                        'selector' => '.welcome-message',
                        'selector_type' => 'css',
                        'description' => 'Verify welcome message appears'
                    ],
                ],
            ],
            [
                'title' => '🛒 E-commerce Checkout Flow',
                'description' => 'Complete checkout process from product selection to order confirmation',
                'priority' => 'high',
                'steps' => [
                    [
                        'action' => 'visit',
                        'value' => '/products',
                        'description' => 'Navigate to products page'
                    ],
                    [
                        'action' => 'click',
                        'selector' => '.product-card:first-child .add-to-cart',
                        'selector_type' => 'css',
                        'description' => 'Add first product to cart'
                    ],
                    [
                        'action' => 'wait',
                        'value' => '1000',
                        'description' => 'Wait for cart update'
                    ],
                    [
                        'action' => 'assert_text',
                        'selector' => '.cart-count',
                        'selector_type' => 'css',
                        'value' => '1',
                        'description' => 'Verify cart count updated'
                    ],
                    [
                        'action' => 'click',
                        'selector' => '.cart-icon',
                        'selector_type' => 'css',
                        'description' => 'Open cart'
                    ],
                    [
                        'action' => 'click',
                        'selector' => '.checkout-button',
                        'selector_type' => 'css',
                        'description' => 'Proceed to checkout'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#shipping-name',
                        'selector_type' => 'css',
                        'value' => 'John Doe',
                        'description' => 'Enter shipping name'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#shipping-address',
                        'selector_type' => 'css',
                        'value' => '123 Main St',
                        'description' => 'Enter shipping address'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#card-number',
                        'selector_type' => 'css',
                        'value' => '4242424242424242',
                        'description' => 'Enter test card number'
                    ],
                    [
                        'action' => 'click',
                        'selector' => '.place-order',
                        'selector_type' => 'css',
                        'description' => 'Place order'
                    ],
                    [
                        'action' => 'wait',
                        'value' => '3000',
                        'description' => 'Wait for order processing'
                    ],
                    [
                        'action' => 'assert_visible',
                        'selector' => '.order-confirmation',
                        'selector_type' => 'css',
                        'description' => 'Verify order confirmation appears'
                    ],
                ],
            ],
            [
                'title' => '📝 Contact Form Submission',
                'description' => 'Test contact form validation and successful submission',
                'priority' => 'medium',
                'steps' => [
                    [
                        'action' => 'visit',
                        'value' => '/contact',
                        'description' => 'Navigate to contact page'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#name',
                        'selector_type' => 'css',
                        'value' => 'John Doe',
                        'description' => 'Enter name'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#email',
                        'selector_type' => 'css',
                        'value' => 'john@example.com',
                        'description' => 'Enter email'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#message',
                        'selector_type' => 'css',
                        'value' => 'This is a test message from QRAFT automated testing.',
                        'description' => 'Enter message'
                    ],
                    [
                        'action' => 'click',
                        'selector' => 'button[type="submit"]',
                        'selector_type' => 'css',
                        'description' => 'Submit form'
                    ],
                    [
                        'action' => 'wait',
                        'value' => '2000',
                        'description' => 'Wait for submission'
                    ],
                    [
                        'action' => 'assert_visible',
                        'selector' => '.success-message',
                        'selector_type' => 'css',
                        'description' => 'Verify success message'
                    ],
                ],
            ],
            [
                'title' => '🔍 Product Search & Filter',
                'description' => 'Test search functionality and filter options on product catalog',
                'priority' => 'medium',
                'steps' => [
                    [
                        'action' => 'visit',
                        'value' => '/products',
                        'description' => 'Navigate to products page'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#search',
                        'selector_type' => 'css',
                        'value' => 'laptop',
                        'description' => 'Enter search term'
                    ],
                    [
                        'action' => 'click',
                        'selector' => '.search-button',
                        'selector_type' => 'css',
                        'description' => 'Click search'
                    ],
                    [
                        'action' => 'wait',
                        'value' => '1500',
                        'description' => 'Wait for search results'
                    ],
                    [
                        'action' => 'assert_visible',
                        'selector' => '.product-card',
                        'selector_type' => 'css',
                        'description' => 'Verify products are displayed'
                    ],
                    [
                        'action' => 'click',
                        'selector' => '#filter-price-high',
                        'selector_type' => 'css',
                        'description' => 'Apply price filter (high to low)'
                    ],
                    [
                        'action' => 'wait',
                        'value' => '1000',
                        'description' => 'Wait for filter to apply'
                    ],
                    [
                        'action' => 'assert_visible',
                        'selector' => '.product-card:first-child',
                        'selector_type' => 'css',
                        'description' => 'Verify filtered results'
                    ],
                ],
            ],
            [
                'title' => '👤 User Registration',
                'description' => 'Complete user registration flow with validation',
                'priority' => 'high',
                'steps' => [
                    [
                        'action' => 'visit',
                        'value' => '/register',
                        'description' => 'Navigate to registration page'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#name',
                        'selector_type' => 'css',
                        'value' => 'John Doe',
                        'description' => 'Enter full name'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#email',
                        'selector_type' => 'css',
                        'value' => 'newuser@example.com',
                        'description' => 'Enter email'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#password',
                        'selector_type' => 'css',
                        'value' => 'SecurePass123!',
                        'description' => 'Enter password'
                    ],
                    [
                        'action' => 'type',
                        'selector' => '#password_confirmation',
                        'selector_type' => 'css',
                        'value' => 'SecurePass123!',
                        'description' => 'Confirm password'
                    ],
                    [
                        'action' => 'check',
                        'selector' => '#terms',
                        'selector_type' => 'css',
                        'description' => 'Accept terms and conditions'
                    ],
                    [
                        'action' => 'click',
                        'selector' => 'button[type="submit"]',
                        'selector_type' => 'css',
                        'description' => 'Submit registration'
                    ],
                    [
                        'action' => 'wait',
                        'value' => '2000',
                        'description' => 'Wait for account creation'
                    ],
                    [
                        'action' => 'assert_url',
                        'value' => '/welcome',
                        'description' => 'Verify redirected to welcome page'
                    ],
                ],
            ],
        ];

        foreach ($templates as $template) {
            TestScenario::create([
                'project_id' => $project->id,
                'title' => $template['title'],
                'description' => $template['description'],
                'priority' => $template['priority'],
                'steps' => $template['steps'],
                'is_active' => true,
            ]);
        }

        $this->command->info('Created 5 test scenario templates!');
    }
}
