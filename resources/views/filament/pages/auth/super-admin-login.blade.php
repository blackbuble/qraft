<x-filament-panels::page.simple>
    <x-slot name="heading">
        <div class="flex flex-col items-center gap-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-red-500 to-red-700 bg-clip-text text-transparent">
                    QRAFT
                </h1>
            </div>
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Super Admin Access
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Platform Administration
                </p>
            </div>
        </div>
    </x-slot>

    {{ $this->form }}

    <x-filament-panels::form.actions :actions="$this->getCachedFormActions()"
        :full-width="$this->hasFullWidthFormActions()" />

    <div class="mt-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="text-sm font-medium text-red-800 dark:text-red-200">
                    Restricted Access
                </p>
                <p class="text-xs text-red-700 dark:text-red-300 mt-1">
                    This area is restricted to super administrators only. Unauthorized access attempts are logged.
                </p>
            </div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="/admin" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
            ← Back to regular login
        </a>
    </div>

    <style>
        /* Red theme for super admin */
        .fi-simple-layout {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 50%, #fca5a5 100%);
            background-attachment: fixed;
        }

        .dark .fi-simple-layout {
            background: linear-gradient(135deg, #1f2937 0%, #111827 50%, #0f172a 100%);
        }

        .fi-simple-page {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(239, 68, 68, 0.2);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .dark .fi-simple-page {
            background: rgba(17, 24, 39, 0.95);
            border: 1px solid rgba(239, 68, 68, 0.1);
        }

        .fi-input-wrapper input:focus {
            border-color: rgb(239, 68, 68);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .fi-btn-primary {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .fi-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.4);
        }
    </style>
</x-filament-panels::page.simple>