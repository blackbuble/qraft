<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestRunFailed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public \App\Models\Run $run
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Test Failure: ' . $this->run->project->name . ' - ' . $this->run->testScenario->title)
            ->error()
            ->line('The test run #' . $this->run->id . ' has failed.')
            ->line('Severity: ' . ucfirst($this->run->severity ?? 'Unknown'))
            ->line('Agent: ' . $this->run->agent->name)
            ->action('View Analysis', \App\Filament\Resources\RunResource::getUrl('view', ['record' => $this->run]))
            ->line('Thank you for using QRAFT.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
