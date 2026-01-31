<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class StripeWebhookController extends CashierController
{
    /**
     * Handle subscription created.
     */
    public function handleCustomerSubscriptionCreated(array $payload): void
    {
        parent::handleCustomerSubscriptionCreated($payload);

        $data = $payload['data']['object'];
        $organization = $this->getOrganizationByStripeId($data['customer']);

        if ($organization) {
            $planName = $this->getPlanNameFromPriceId($data['items']['data'][0]['price']['id']);

            $organization->update([
                'subscription_plan' => $planName,
                'subscription_status' => 'active',
            ]);

            Log::info('Subscription created', [
                'organization_id' => $organization->id,
                'plan' => $planName,
            ]);
        }
    }

    /**
     * Handle subscription updated.
     */
    public function handleCustomerSubscriptionUpdated(array $payload): void
    {
        parent::handleCustomerSubscriptionUpdated($payload);

        $data = $payload['data']['object'];
        $organization = $this->getOrganizationByStripeId($data['customer']);

        if ($organization) {
            $planName = $this->getPlanNameFromPriceId($data['items']['data'][0]['price']['id']);
            $status = $data['status'];

            $organization->update([
                'subscription_plan' => $planName,
                'subscription_status' => $status,
            ]);

            Log::info('Subscription updated', [
                'organization_id' => $organization->id,
                'plan' => $planName,
                'status' => $status,
            ]);
        }
    }

    /**
     * Handle subscription deleted.
     */
    public function handleCustomerSubscriptionDeleted(array $payload): void
    {
        parent::handleCustomerSubscriptionDeleted($payload);

        $data = $payload['data']['object'];
        $organization = $this->getOrganizationByStripeId($data['customer']);

        if ($organization) {
            $organization->update([
                'subscription_plan' => 'free',
                'subscription_status' => 'canceled',
            ]);

            Log::info('Subscription deleted', [
                'organization_id' => $organization->id,
            ]);
        }
    }

    /**
     * Handle payment succeeded.
     */
    public function handleInvoicePaymentSucceeded(array $payload): void
    {
        $data = $payload['data']['object'];
        $organization = $this->getOrganizationByStripeId($data['customer']);

        if ($organization) {
            Log::info('Payment succeeded', [
                'organization_id' => $organization->id,
                'amount' => $data['amount_paid'] / 100,
                'invoice_id' => $data['id'],
            ]);

            // TODO: Send payment success notification
        }
    }

    /**
     * Handle payment failed.
     */
    public function handleInvoicePaymentFailed(array $payload): void
    {
        $data = $payload['data']['object'];
        $organization = $this->getOrganizationByStripeId($data['customer']);

        if ($organization) {
            $organization->update([
                'subscription_status' => 'past_due',
            ]);

            Log::warning('Payment failed', [
                'organization_id' => $organization->id,
                'invoice_id' => $data['id'],
            ]);

            // TODO: Send payment failure notification to organization owner
        }
    }

    /**
     * Get organization by Stripe customer ID.
     */
    protected function getOrganizationByStripeId(string $stripeId): ?Organization
    {
        return Organization::where('stripe_id', $stripeId)->first();
    }

    /**
     * Get plan name from Stripe price ID.
     */
    protected function getPlanNameFromPriceId(string $priceId): string
    {
        // Map Stripe price IDs to plan names
        $priceMap = [
            config('services.stripe.price_pro') => 'pro',
            config('services.stripe.price_enterprise') => 'enterprise',
        ];

        return $priceMap[$priceId] ?? 'free';
    }
}
