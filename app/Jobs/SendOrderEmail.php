<?php

namespace App\Jobs;

use App\Mail\OrderMade;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendOrderEmail implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;

    /** @var Order */
    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
      $email = new OrderMade($this->order->invoice);
      Mail::to($this->order->user->email)->send($email);
    }
}
