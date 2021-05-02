<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RecivedMoneySuccess extends Notification
{
    use Queueable;
    public $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //

        $this->data= $data;


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
        $data = $this->data;

        return (new MailMessage)
            ->subject('Funds Transfer Successful')
            ->greeting('Hi '. $data->receiver_name.'!')
            ->line("You just received a sum of money from another user on Financely. Below are the transaction info:")
            ->line('Amount: $'.$data->amount)
            ->line('Sender Name: '.$data->sender_name)
            ->line('Sender Email: '.$data->sender_email)
            ->line("The money has been automatically deposited into your account. For more details please login your account by clicking the button below.")
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
