<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCodeResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $token;
    public function __construct($user,$token)
    {
       $this -> user = $user;
       $this -> token = $token;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Code Reset Password',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
            return new Content(
                view: 'mailResetPassword',
                with: [
                    'name' => $this->user -> name,
                    'token' => $this->token
                ],
            
    
            );
     
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
