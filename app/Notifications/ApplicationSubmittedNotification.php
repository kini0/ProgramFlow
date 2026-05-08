<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(public Application $application)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation de votre candidature — '.$this->application->program->title)
            ->greeting('Bonjour '.$notifiable->first_name.',')
            ->line('Nous avons bien reçu votre candidature au programme **'.$this->application->program->title.'**.')
            ->line('Référence de candidature : **'.$this->application->reference.'**')
            ->line('Notre jury va étudier votre dossier avec attention. Vous serez notifiée par email dès qu\'une décision aura été prise.')
            ->action('Suivre ma candidature', url('/candidate/applications/'.$this->application->reference))
            ->line('Merci pour votre engagement et bonne chance !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'application_id'  => $this->application->id,
            'reference'       => $this->application->reference,
            'program_title'   => $this->application->program->title,
            'message'         => 'Votre candidature a bien été soumise.',
        ];
    }
}
