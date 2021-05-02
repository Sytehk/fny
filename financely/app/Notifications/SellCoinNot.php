<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SellCoinNot extends Notification
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

                    ->subject('Cryptocurrency Transaction')
                    ->greeting('Hello '. $user->name.'!')
                    ->line("We have received your Request. Please kindly wait patiently as we quickly verify your transaction.")
                    ->line("Once Verification is complete, the requsted ammount will be remitted to the specified account details. You can check transaction status from your Dashbaord. To login your account click login now button below. ")
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
