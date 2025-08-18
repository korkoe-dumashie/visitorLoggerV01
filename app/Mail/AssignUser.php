<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class AssignUser extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    // public $token;
    public $resetUrl;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $token)
    {
        //
        $this->user = $user;
        // $this->token = $token;
        // $this->resetUrl = route('reset-password', [
        //     'token' => $token,
        //     'email' => $user->email
        // ]);

        $this->resetUrl = URL::temporarySignedRoute(
            'password.reset',
            now()->addMinutes(60),
            [
                'token'=>$token,
                'email'=>$user->email
            ]
            );

        Log::debug($this->resetUrl);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('korkoedumashie@gmail.com','Visitor Mgt'),
            subject: 'Your User Account Has Been Created'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'users.mail',
            with:[
                'user'=>$this->user,
                // 'token'=>$this->token,
                'resetUrl'=>$this->resetUrl
            ]
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
