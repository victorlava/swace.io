<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\OrderMade;
use Mail;

class SendOrderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    protected $user;

    protected $status;

    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $status, $message)
    {
        $this->user = $user;
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $email = new OrderMade($this->status, $this->message);
      Mail::to($this->user->email)->send($email);
    }
}
