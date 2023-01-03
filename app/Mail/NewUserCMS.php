<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUserCMS extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Your password - Abbott OCT';

    public $userApp;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userApp,$password)
    {
        $this->userApp = $userApp;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.newusercms');
    }
}
