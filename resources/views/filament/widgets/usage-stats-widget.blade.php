<x-filament-widgets::widget>
    @php
        $stats = $this->getUsageStats();
        $showUpgrade = $this->shouldShowUpgradePrompt();
        $recommendedPlan = $this->getRecommendedPlan();
        $organization = \Filament\Facades\Filament::getTenant();
    @endphp

    <x-filament::section>
        <x-slot name="heading">
            Usage & Limits
        </x-slot>

        <x-slot name="description">
            Monitor your current usage against plan limits
        </x-slot>

        @if($showUpgrade)
            <div
                class="mb-6 rounded-lg bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 p-4">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-exclamation-triangle
                        class="w-5 h-5 text-warning-600 dark:text-warning-400 mt-0.5 flex-shrink-0" />
                    <div class="flex-1">
                        <p class="font-medium text-warning-900 dark:text-warning-100">
                            You're approaching your plan limits
                        </p>
                        <p class="text-sm text-warning-700 dark:text-warning-300 mt-1">
                            Upgrade to {{ ucfirst($recommendedPlan) }} plan for higher limits and more features.
                        </p>
                        <a href="{{ route('filament.admin.pages.manage-subscription') }}"
                            class="inline-flex items-center gap-1 text-sm font-medium text-warning-700 dark:text-warning-300 hover:text-warning-900 dark:hover:text-warning-100 mt-2">
                            Upgrade Now
                            <x-heroicon-o-arrow-right class="w-4 h-4" />
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($stats as $feature => $stat)
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ ucwords(str_replace('_', ' ', str_replace('_per_month', '', $feature))) }}
                        </p>
                        @if($stat['unlimited'])
                            <span
                                class="inline-flex items-center rounded-full bg-success-100 dark:bg-success-900/30 px-2 py-0.5 text-xs font-medium text-success-700 dark:text-success-300">
                                Unlimited
                            </span>
                        @endif
                    </div>

                    <p class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ number_format($stat['usage']) }}
                        @if(!$stat['unlimited'])
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                / {{ number_format($stat['limit']) }}
                            </span>
                        @endif
                    </p>

                    @if(!$stat['unlimited'])
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
                            <div class="h-2 rounded-full transition-all {{ $stat['percentage'] >= 80 ? 'bg-warning-500' : ($stat['percentage'] >= 100 ? 'bg-danger-500' : 'bg-primary-500') }}"
                                style="width: {{ min($stat['percentage'], 100) }}%"></div>
                        </div>

                        <p
                            class="text-xs {{ $stat['percentage'] >= 80 ? 'text-warning-600 dark:text-warning-400' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ number_format($stat['percentage'], 1) }}% used
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

        @if($organization->onTrial())
            <div
                class="mt-4 rounded-lg bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 p-4">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-information-circle
                        class="w-5 h-5 text-primary-600 dark:text-primary-400 mt-0.5 flex-shrink-0" />
                    <div>
                        <p class="text-sm text-primary-900 dark:text-primary-100">
                            <strong>Trial Period:</strong> Your trial ends
                            {{ $organization->trial_ends_at->diffForHumans() }}
                            ({{ $organization->trial_ends_at->format('M d, Y') }})
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>