<div class="space-y-4">
    @php
        $organization = \Filament\Facades\Filament::getTenant();
        $planLimits = app(\App\Services\PlanLimits::class);
        $stats = $planLimits->getUsageStats($organization);
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Current Plan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ ucfirst($organization->subscription_plan) }}
                    </p>
                </div>
                <div class="rounded-full bg-primary-100 dark:bg-primary-900 p-3">
                    <x-heroicon-o-star class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ ucfirst($organization->subscription_status) }}
                    </p>
                </div>
                <div class="rounded-full bg-success-100 dark:bg-success-900 p-3">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-success-600 dark:text-success-400" />
                </div>
            </div>
        </div>
    </div>

    @if($organization->onTrial())
        <div class="rounded-lg bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 p-4">
            <div class="flex items-start gap-3">
                <x-heroicon-o-clock class="w-5 h-5 text-warning-600 dark:text-warning-400 mt-0.5" />
                <div>
                    <p class="font-medium text-warning-900 dark:text-warning-100">Trial Period</p>
                    <p class="text-sm text-warning-700 dark:text-warning-300 mt-1">
                        Your trial ends on {{ $organization->trial_ends_at->format('M d, Y') }}
                        ({{ $organization->trial_ends_at->diffForHumans() }})
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-3">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Usage This Month</h3>

        @foreach($stats as $feature => $stat)
            <div class="space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-700 dark:text-gray-300">
                        {{ ucwords(str_replace('_', ' ', $feature)) }}
                    </span>
                    <span class="font-medium text-gray-900 dark:text-white">
                        {{ $stat['usage'] }} / {{ $stat['unlimited'] ? '∞' : $stat['limit'] }}
                    </span>
                </div>

                @if(!$stat['unlimited'])
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all {{ $stat['percentage'] >= 80 ? 'bg-warning-500' : 'bg-primary-500' }}"
                            style="width: {{ min($stat['percentage'], 100) }}%"></div>
                    </div>

                    @if($stat['percentage'] >= 80)
                        <p class="text-xs text-warning-600 dark:text-warning-400">
                            ⚠️ You're approaching your limit. Consider upgrading your plan.
                        </p>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
</div>