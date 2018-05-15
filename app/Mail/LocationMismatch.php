<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LocationMismatch extends Mailable
{
    use Queueable, SerializesModels;

    private $ip;
    private $currentIP;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ip, $currentIP)
    {
        $this->ip = $ip;
        $this->current_ip = $currentIP;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.mismatch', ['ip' => $this->ip, 'currentIP' => $this->current_ip]);
    }
}
