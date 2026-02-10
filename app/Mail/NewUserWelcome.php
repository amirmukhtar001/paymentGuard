<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class NewUserWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $role;
    public $url;

    public function __construct(User $user, $password, $role, $url)
    {
        $this->user = $user;
        $this->password = $password;
        $this->role = $role;
        $this->url = $url;
    }

    public function build()
    {
        return $this->subject('Welcome to Agriculture Portal')
            ->view('emails.new-user-welcome');
    }
}
