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

    protected $viewOrder;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct(string $status, string $viewOrder)
     {
         $this->status = $status;
         $this->viewOrder = $viewOrder;

     }

    /**
     * Build the message.
     *
     * @return $this
     */
     public function build()
     {
         return $this->view('emails.order-made', ['order_status' => $this->status,
                                                  'order_link' => $this->viewOrder]);
     }
}
