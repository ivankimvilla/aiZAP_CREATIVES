<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPasswordResetMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

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
        return $this->subject('Reset your aiZAP admin password')
            ->view('emails.admin-password-reset');
    }
}
