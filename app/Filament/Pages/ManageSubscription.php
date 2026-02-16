<?php

namespace App\Filament\Pages;

use App\Models\Organization;
use App\Services\PlanLimits;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSubscription extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static string $view = 'filament.pages.manage-subscription';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $organization = Filament::getTenant();

        $this->form->fill([
            'current_plan' => $organization->subscription_plan,
        ]);
    }

    public function form(Form $form): Form
    {
        $organization = Filament::getTenant();
        $plans = $organization->subscriptionPlans();

        return $form
            ->schema([
                Section::make('Current Subscription')
                    ->description('Manage your subscription plan and billing')
                    ->schema([
                        ViewField::make('current_subscription')
                            ->view('filament.forms.components.subscription-status'),
                    ]),

                Section::make('Available Plans')
                    ->description('Choose the plan that fits your needs')
                    ->schema([
                        Radio::make('selected_plan')
                            ->options([
                                'free' => 'Free - Perfect for trying out QRAFT',
                                'pro' => 'Pro - $49/month - For growing teams',
                                'enterprise' => 'Enterprise - $299/month - For large organizations',
                            ])
                            ->descriptions([
                                'free' => '1 project, 100 test runs/month, 3 team members',
                                'pro' => '10 projects, 5,000 test runs/month, 10 team members',
                                'enterprise' => 'Unlimited everything + priority support',
                            ])
                            ->default($organization->subscription_plan)
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        $organization = Filament::getTenant();
        $currentPlan = $organization->subscription_plan;

        return [
            Action::make('upgrade')
                ->label('Upgrade Plan')
                ->visible(fn() => $this->data['selected_plan'] !== $currentPlan)
                ->action('upgradePlan')
                ->requiresConfirmation()
                ->modalHeading('Upgrade Subscription')
                ->modalDescription('You will be redirected to Stripe to complete the payment.')
                ->color('success'),

            Action::make('manage_billing')
                ->label('Manage Billing')
                ->url(fn() => $this->getBillingPortalUrl())
                ->openUrlInNewTab()
                ->color('gray')
                ->icon('heroicon-o-arrow-top-right-on-square'),
        ];
    }

    public function upgradePlan(): void
    {
        $organization = Filament::getTenant();
        $selectedPlan = $this->data['selected_plan'];

        if ($selectedPlan === 'free') {
            // Downgrade to free
            $organization->subscription('default')?->cancel();
            $organization->update(['subscription_plan' => 'free']);

            Notification::make()
                ->title('Subscription Cancelled')
                ->body('You have been downgraded to the free plan.')
                ->success()
                ->send();

            return;
        }

        // Redirect to Stripe Checkout
        $this->redirect($this->getCheckoutUrl($selectedPlan));
    }

    protected function getCheckoutUrl(string $plan): string
    {
        $organization = Filament::getTenant();
        $plans = $organization->subscriptionPlans();
        $priceId = $plans[$plan]['stripe_price_id'] ?? null;

        if (!$priceId) {
            Notification::make()
                ->title('Configuration Error')
                ->body('Stripe price ID not configured for this plan.')
                ->danger()
                ->send();

            return '#';
        }

        try {
            return $organization
                ->newSubscription('default', $priceId)
                ->checkout([
                    'success_url' => route('filament.admin.pages.manage-subscription') . '?success=true',
                    'cancel_url' => route('filament.admin.pages.manage-subscription') . '?canceled=true',
                ]);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Checkout Error')
                ->body('Unable to create checkout session: ' . $e->getMessage())
                ->danger()
                ->send();

            return '#';
        }
    }

    protected function getBillingPortalUrl(): string
    {
        $organization = Filament::getTenant();

        try {
            return $organization->billingPortalUrl(
                route('filament.admin.pages.manage-subscription')
            );
        } catch (\Exception $e) {
            return '#';
        }
    }

    public static function getNavigationLabel(): string
    {
        return 'Subscription';
    }

    public function getTitle(): string
    {
        return 'Manage Subscription';
    }
}
