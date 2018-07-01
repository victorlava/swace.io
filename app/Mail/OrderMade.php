<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderMade extends Mailable
{
    use Queueable, SerializesModels;

    protected $status;

    protected $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct($status, $message)
     {
         $this->status = $status;
         $this->message = $message;

     }

    /**
     * Build the message.
     *
     * @return $this
     */
     public function build()
     {
         return $this->view('emails.order-made', ['order_status' => $this->status,
                                                  'order_message' => $this->message]);
     }
}
