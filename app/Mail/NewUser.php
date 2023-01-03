<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUser extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Confirm registration - Abbott OCT';

    public $userApp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userApp)
    {
        $this->userApp = $userApp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.newuser');
    }
}
