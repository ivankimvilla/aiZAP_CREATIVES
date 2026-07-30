<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class AdminPasswordResetMail extends Mailable
{
    public User $user;
    public string $token;
    public string $resetUrl;

    public function __construct(User $user, string $token, string $resetUrl)
    {
        $this->user = $user;
        $this->token = $token;
        $this->resetUrl = $resetUrl;
    }

    public function build(): self
    {
        return $this->from(config('mail.from.address', 'hello@aizapcreatives.com'), config('mail.from.name', 'aiZAP Creatives'))
            ->replyTo(config('mail.from.address', config('mail.from.name', 'aiZAP Creatives')))
            ->subject('Reset your aiZAP admin password')
            ->view('emails.admin-password-reset');
    }
}
