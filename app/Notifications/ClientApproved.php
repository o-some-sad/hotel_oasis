<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientApproved extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
        \Log::info('Preparing email for: '.$notifiable->email);
        
        try {
            return (new MailMessage)
                ->subject('Welcome to Hotel Oasis 🎉! Your Account Has Been Approved')
                ->greeting('Hello ' . $notifiable->name . ',')
                ->line('We are excited to inform you that your account has been approved!')
                ->line('Thank you for choosing Hotel Oasis.');
        } catch (\Exception $e) {
            \Log::error('Email error: '.$e->getMessage());
            throw $e; // Re-throw to see the error in your logs
        }
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
