<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class UsageLimitNotification extends Notification
{
    use Queueable;

    protected $usagePercentage;
    protected $currentRuns;
    protected $limit;

    public function __construct(int $currentRuns, int $limit)
    {
        $this->currentRuns = $currentRuns;
        $this->limit = $limit;
        $this->usagePercentage = ($currentRuns / $limit) * 100;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->usagePercentage >= 100
            ? '⚠️ You\'ve reached your test run limit'
            : '⚠️ You\'re approaching your test run limit';

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("You've used **{$this->currentRuns} out of {$this->limit}** test runs this month ({$this->usagePercentage}%).");

        if ($this->usagePercentage >= 100) {
            $message->line('Your account has reached the monthly limit. Upgrade to Pro for unlimited test runs!')
                ->action('Upgrade to Pro - $99/mo', url('/admin/settings#billing'))
                ->line('With Pro, you also get:')
                ->line('• AI-powered test generation')
                ->line('• Flakiness intelligence dashboard')
                ->line('• Priority support')
                ->line('• Scheduled test runs');
        } else {
            $message->line('You\'re running low on test runs. Consider upgrading to Pro for unlimited runs!')
                ->action('View Pricing', url('/admin/settings#billing'));
        }

        return $message->line('Thank you for using QRAFT!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'usage_limit',
            'current_runs' => $this->currentRuns,
            'limit' => $this->limit,
            'percentage' => $this->usagePercentage,
            'message' => "You've used {$this->currentRuns}/{$this->limit} test runs ({$this->usagePercentage}%)",
        ];
    }

    /**
     * Send Filament notification
     */
    public static function sendFilamentNotification($user, int $currentRuns, int $limit)
    {
        $percentage = ($currentRuns / $limit) * 100;

        if ($percentage >= 100) {
            FilamentNotification::make()
                ->warning()
                ->title('Test Run Limit Reached')
                ->body("You've used all {$limit} test runs this month. Upgrade to Pro for unlimited runs!")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('upgrade')
                        ->button()
                        ->url('/admin/settings#billing')
                        ->label('Upgrade to Pro'),
                ])
                ->persistent()
                ->sendToDatabase($user);
        } elseif ($percentage >= 95) {
            FilamentNotification::make()
                ->warning()
                ->title('Almost at Limit')
                ->body("You've used {$currentRuns}/{$limit} test runs ({$percentage}%). Consider upgrading to Pro!")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view_pricing')
                        ->button()
                        ->url('/admin/settings#billing')
                        ->label('View Pricing'),
                ])
                ->sendToDatabase($user);
        } elseif ($percentage >= 80) {
            FilamentNotification::make()
                ->info()
                ->title('Usage Alert')
                ->body("You've used {$currentRuns}/{$limit} test runs ({$percentage}%).")
                ->sendToDatabase($user);
        }
    }
}
