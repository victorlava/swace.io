<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMade extends Mailable
{
    use Queueable, SerializesModels;

    /** @var string */
    protected $invoiceUrl;

    public function __construct(string $invoiceUrl)
    {
        $this->invoiceUrl = $invoiceUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->view('emails.order-made', ['invoiceUrl' => $this->invoiceUrl]);
    }
}
