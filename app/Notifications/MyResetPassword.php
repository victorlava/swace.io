<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Mail\PasswordReset;

class MyResetPassword extends Notification
{
    use Queueable;

    protected $token;
    protected $ip;
    protected $email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ip, $token, $email)
    {
        $this->ip = $ip;
        $this->token = $token;
        $this->email = $email;
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
        return (new MailMessage)->subject('Reset Password')->view(
        'emails.password-reset',
            [   'ip' => $this->ip,
                'token' => $this->token,
                'email' => $this->email]
        );
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
