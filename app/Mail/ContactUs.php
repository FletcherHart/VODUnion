<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    public $user_email;
    public $subject;
    public $subject2;
    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $subject, $body, $subject2 = null)
    {
        $this->user_email = $email;
        $this->subject = $subject;
        if($subject2 == null) {
            $this->subject2 = $subject;
        } else {
            $this->subject2 = $subject2;
        }
        
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('contact');
    }
}
