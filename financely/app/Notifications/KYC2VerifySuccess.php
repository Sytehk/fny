<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class KYC2VerifySuccess extends Notification
{
    use Queueable;
    public $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $this->user;

        return (new MailMessage)

            ->subject('Identity Verified Successfully')
            ->greeting('Congratulations '. $user->name.'!')
            ->line("Your Identity Verification Request has been successfully approved. Thanks for your patience.")
            ->line("You are now a bonafied member of Financely. All restrictions on your account have been removed. You can see verification status in your dashboard. To login your account click login now button below. ")
            ->action('Login Now', route('login'))
            ->line('Thank you for using our website! If you face any problems feel free to contact us anytime.');
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
