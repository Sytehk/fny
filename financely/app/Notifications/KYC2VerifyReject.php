<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class KYC2VerifyReject extends Notification
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

             ->subject('Identity Verification Request Denied')
            ->greeting('Sorry '. $user->name.'!')
            ->line("Unfortunately We couldn't verify your identity. You have 3 attempts to do so. After 3 request you will get permanently banned. Please send us a clear photo of your Goverment issued ID. You could hold the ID and take a selfie with your face and the ID clearly visble to gain more authenticity.")
            ->line("We require only accurate and real identity info. ID must not be expired or forged. We do not wish to do business with anonymous users. To submit another application please login in to your account. For login click login now button below. ")
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
