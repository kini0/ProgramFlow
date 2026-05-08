<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Evaluation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EvaluationAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(public Evaluation $evaluation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle candidature à évaluer')
            ->greeting('Bonjour '.$notifiable->first_name.',')
            ->line('Une nouvelle candidature vous a été attribuée pour évaluation.')
            ->line('Programme : **'.$this->evaluation->application->program->title.'**')
            ->line('Référence : '.$this->evaluation->application->reference)
            ->action('Évaluer la candidature', url('/jury/evaluations/'.$this->evaluation->id))
            ->line('Merci pour votre contribution.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'evaluation_id'  => $this->evaluation->id,
            'reference'      => $this->evaluation->application->reference,
            'program_title'  => $this->evaluation->application->program->title,
            'message'        => 'Nouvelle candidature à évaluer.',
        ];
    }
}
