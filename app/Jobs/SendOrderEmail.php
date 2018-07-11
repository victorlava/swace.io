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

    protected $viewOrder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(App\User $user, string $status, string $viewOrder)
    {
        $this->user = $user;
        $this->status = $status;
        $this->viewOrder = $viewOrder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $email = new OrderMade($this->status, $this->viewOrder);
      Mail::to($this->user->email)->send($email);
    }
}
