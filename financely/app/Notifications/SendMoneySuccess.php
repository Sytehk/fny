<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendMoneySuccess extends Notification
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

            ->subject('Funds Sent Successfully')
            ->greeting('Hi '. $data->user_name.'!')
            ->line("Your requested transfer to .$data->receiver_name was successful.  View transaction info below:")
            ->line('Total Amount: $'.$data->amount)
            ->line('Charge: $'.$data->charge)
            ->line('Net Amount: $'.$data->new_amount)
            ->line('Receiver Name: '.$data->receiver_name)
            ->line('Receiver Email: '.$data->receiver_email)
            ->line("Funds sent are credited to the recievers account instantly. You could confirm from the reciever. For more detailed info, please login your account by clicking the button below.")
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
