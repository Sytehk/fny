<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class KYCVerifyAccept extends Notification
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

                    ->subject('Proof of Address Verification Request')
                    ->greeting('Hello '. $user->name.'!')
                    ->line("We have received your Proof of Address Verification Request. We will review your submission within 48 hours. Feel free to continue exploring our site while your verification process is ongoing.")
                    ->line("Once Verification is complete you will be notified via email. Also you can see verification status in your dashboard. To login to your account click login now button below. ")
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
