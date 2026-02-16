<?php

namespace App\Http\Middleware;

use App\Services\PlanLimits;
use Closure;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimits
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $organization = Filament::getTenant();

        if (!$organization) {
            return $next($request);
        }

        $planLimits = app(PlanLimits::class);

        $canProceed = match ($feature) {
            'project' => $planLimits->canCreateProject($organization),
            'test_run' => $planLimits->canRunTest($organization),
            'team_member' => $planLimits->canAddTeamMember($organization),
            'ai_generation' => $planLimits->canGenerateAiTest($organization),
            default => true,
        };

        if (!$canProceed) {
            Notification::make()
                ->title('Plan Limit Reached')
                ->body("You've reached your plan limit for {$feature}s. Please upgrade your plan to continue.")
                ->warning()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('upgrade')
                        ->label('Upgrade Plan')
                        ->url(route('filament.admin.pages.manage-subscription'))
                        ->button(),
                ])
                ->persistent()
                ->send();

            return redirect()->back();
        }

        // Show warning if approaching limit (>= 80%)
        $percentage = $planLimits->getUsagePercentage($organization, $feature . 's');

        if ($percentage >= 80 && $percentage < 100) {
            Notification::make()
                ->title('Approaching Plan Limit')
                ->body("You're using {$percentage}% of your {$feature} limit. Consider upgrading your plan.")
                ->warning()
                ->send();
        }

        return $next($request);
    }
}
