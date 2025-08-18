<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content,Address,Envelope};
use Illuminate\Queue\SerializesModels;

class UpdateRole extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public $roleName;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $roleName)
    {
        //
        $this->user = $user;
        $this->roleName = $roleName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('korkoedumashie@gmail.com','Visitor Mgt'),
            subject: 'Your role has been changed'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'users.update-mail',
            with: ['users'=>$this->user]
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
