<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewBidMail extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(protected string $comment){}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.new-bid', ['comment' => $this->comment]);
    }
}
