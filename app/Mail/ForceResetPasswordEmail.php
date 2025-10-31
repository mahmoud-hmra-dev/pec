<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForceResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $new_password;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param $new_password
     * @param $user
     */
    public function __construct($new_password,$user)
    {
        $this->new_password = $new_password;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Password')
            ->view('emails.new_password',['new_password' , $this->new_password,'user'=>$this->user]);
    }
}
