<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationDecisionNotification extends Notification
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
        $msg = (new MailMessage)
            ->subject('Décision sur votre candidature — '.$this->application->program->title)
            ->greeting('Bonjour '.$notifiable->first_name.',');

        $msg = match ($this->application->status) {
            ApplicationStatus::Accepted   => $msg
                ->line('Félicitations ! Votre candidature au programme **'.$this->application->program->title.'** a été **acceptée**.')
                ->line('Vous recevrez prochainement les détails de la suite du programme.')
                ->action('Accéder à mon espace', url('/candidate/applications/'.$this->application->reference)),
            ApplicationStatus::Rejected   => $msg
                ->line('Nous regrettons de vous informer que votre candidature au programme **'.$this->application->program->title.'** n\'a pas été retenue cette fois-ci.')
                ->line('Nous vous encourageons à postuler aux prochaines éditions.'),
            ApplicationStatus::Waitlisted => $msg
                ->line('Votre candidature au programme **'.$this->application->program->title.'** a été placée en **liste d\'attente**.')
                ->line('Vous serez recontactée si une place se libère.'),
            ApplicationStatus::Shortlisted => $msg
                ->line('Bonne nouvelle : votre candidature au programme **'.$this->application->program->title.'** a été **présélectionnée**.')
                ->line('Une décision finale vous sera communiquée prochainement.'),
            default => $msg->line('Le statut de votre candidature a été mis à jour.'),
        };

        if ($this->application->decision_reason) {
            $msg->line('Commentaire : '.$this->application->decision_reason);
        }

        return $msg->salutation('Cordialement, '.config('programflow.foundation_name'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'reference'      => $this->application->reference,
            'status'         => $this->application->status->value,
            'message'        => 'Décision rendue sur votre candidature : '.$this->application->status->label(),
        ];
    }
}
