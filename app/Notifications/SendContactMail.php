<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendContactMail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contact $contact)
    {
        //
        $this->contact = $contact;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        //contact
        $contact = $this->contact;
        return (new MailMessage)
            ->subject('Contact Mail')
            ->markdown('mail.contact', [
                'email' => $contact->email,
                'name' => $contact->name,
                'surname' => $contact->surname,
                'phone' => $contact->phone,
                'message' => $contact->message,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
