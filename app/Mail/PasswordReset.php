<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $ip;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $ip)
    {
        $this->user = $user;
        $this->ip = $ip;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.password-reset', [   'user' => $this->user,
                                                        'ip' => $this->ip]);
    }
}
