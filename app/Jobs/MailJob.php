<?php

namespace App\Jobs;

use App\Mail\NewBidMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected string $email,
        protected string $comment
    ){}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       echo "TEST";
        //Mail::to($this->email)->send(new NewBidMail($this->comment));
    }
}
