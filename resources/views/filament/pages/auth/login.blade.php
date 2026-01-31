<x-filament-panels::page.simple>
    <x-slot name="heading">
        <div class="flex flex-col items-center gap-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1
                    class="text-3xl font-bold bg-gradient-to-r from-amber-500 to-orange-600 bg-clip-text text-transparent">
                    QRAFT
                </h1>
            </div>
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Welcome Back
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    AI-Powered Quality Intelligence Platform
                </p>
            </div>
        </div>
    </x-slot>

    {{ $this->form }}

    <x-filament-panels::form.actions :actions="$this->getCachedFormActions()"
        :full-width="$this->hasFullWidthFormActions()" />

    @if($this->hasRegistration())
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Don't have an account?
                <a href="{{ $this->getRegistrationUrl() }}"
                    class="font-medium text-amber-600 hover:text-amber-500 dark:text-amber-400 dark:hover:text-amber-300">
                    Sign up
                </a>
            </p>
        </div>
    @endif

    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <div class="text-center space-y-3">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Demo Accounts for Testing:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                    <p class="font-semibold text-gray-700 dark:text-gray-300">Super Admin</p>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">admin@qraft.test</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                    <p class="font-semibold text-gray-700 dark:text-gray-300">Org Owner (Pro)</p>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">owner@qraft.test</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                    <p class="font-semibold text-gray-700 dark:text-gray-300">Org Member</p>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">member@qraft.test</p>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 italic">
                All demo accounts use password: <code
                    class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">password</code>
            </p>
        </div>
    </div>

    <style>
        /* Custom animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fi-simple-page {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Gradient background */
        .fi-simple-layout {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 50%, #fcd34d 100%);
            background-attachment: fixed;
        }

        .dark .fi-simple-layout {
            background: linear-gradient(135deg, #1f2937 0%, #111827 50%, #0f172a 100%);
        }

        /* Card styling */
        .fi-simple-page {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(251, 191, 36, 0.2);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .dark .fi-simple-page {
            background: rgba(17, 24, 39, 0.95);
            border: 1px solid rgba(251, 191, 36, 0.1);
        }

        /* Input focus effects */
        .fi-input-wrapper input:focus {
            border-color: rgb(251, 191, 36);
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1);
        }

        /* Button hover effects */
        .fi-btn-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            transition: all 0.3s ease;
        }

        .fi-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.4);
        }
    </style>
</x-filament-panels::page.simple>