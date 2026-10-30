<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainerCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $trainer,
        public string $plainPassword,
        public string $loginUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Bergabung! Kredensial Akun Personal Trainer FitCore Gym',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trainer-credentials',
        );
    }
}
