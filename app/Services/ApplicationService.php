<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Program;
use App\Models\User;
use App\Notifications\ApplicationDecisionNotification;
use App\Notifications\ApplicationSubmittedNotification;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ApplicationService
{
    public function __construct(private ApplicationRepositoryInterface $applications)
    {
    }

    public function startOrResumeDraft(Program $program, User $user): Application
    {
        if (! $program->isAcceptingApplications()) {
            throw new RuntimeException('Les candidatures pour ce programme ne sont pas ouvertes.');
        }

        $existing = $this->applications->findDraftFor($program, $user);
        if ($existing) {
            return $existing;
        }

        return $this->applications->create([
            'program_id' => $program->id,
            'user_id'    => $user->id,
            'status'     => ApplicationStatus::Draft->value,
        ]);
    }

    /**
     * Sauvegarde les réponses (mode brouillon ou validation finale).
     *
     * @param  array<string, mixed>  $responses  ['field_id' => valeur]
     * @param  array<string, UploadedFile|UploadedFile[]>  $files
     */
    public function saveResponses(Application $application, array $responses, array $files = []): Application
    {
        return DB::transaction(function () use ($application, $responses, $files) {
            foreach ($responses as $fieldId => $value) {
                $isJson = is_array($value);
                $application->responses()->updateOrCreate(
                    ['application_field_id' => $fieldId],
                    [
                        'value'      => $isJson ? null : (string) $value,
                        'value_json' => $isJson ? $value : null,
                    ],
                );
            }

            foreach ($files as $key => $file) {
                $field = $application->program->applicationFields()->where('key', $key)->first()
                    ?? $application->program->applicationFields()->find($key);

                if (! $field || ! $file instanceof UploadedFile) {
                    continue;
                }

                $path = $file->store('applications/'.$application->id, 'documents');
                $application->documents()->create([
                    'label'         => $field->label,
                    'original_name' => $file->getClientOriginalName(),
                    'disk'          => 'documents',
                    'path'          => $path,
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                    'category'      => $field->key,
                    'uploaded_by'   => $application->user_id,
                ]);

                $application->responses()->updateOrCreate(
                    ['application_field_id' => $field->id],
                    ['value' => $path],
                );
            }

            return $application->refresh();
        });
    }

    public function submit(Application $application): Application
    {
        if ($application->status !== ApplicationStatus::Draft) {
            throw new RuntimeException('Cette candidature est déjà soumise.');
        }

        $application->update([
            'status'       => ApplicationStatus::Submitted->value,
            'submitted_at' => now(),
        ]);

        $application->candidate?->notify(new ApplicationSubmittedNotification($application));

        return $application->refresh();
    }

    public function withdraw(Application $application): Application
    {
        $application->update([
            'status' => ApplicationStatus::Withdrawn->value,
        ]);
        return $application;
    }

    public function decide(
        Application $application,
        ApplicationStatus $status,
        ?string $reason,
        User $decidedBy,
    ): Application {
        if (! in_array($status, [
            ApplicationStatus::Accepted,
            ApplicationStatus::Rejected,
            ApplicationStatus::Waitlisted,
            ApplicationStatus::Shortlisted,
        ], true)) {
            throw new RuntimeException('Statut de décision invalide.');
        }

        $application->update([
            'status'          => $status->value,
            'decision_reason' => $reason,
            'decided_at'      => now(),
            'decided_by'      => $decidedBy->id,
        ]);

        $application->candidate?->notify(new ApplicationDecisionNotification($application));

        return $application->refresh();
    }

    public function recomputeScores(Application $application): Application
    {
        $submitted = $application->evaluations()->where('status', 'submitted')->get();
        if ($submitted->isEmpty()) {
            $application->update([
                'average_score'     => null,
                'evaluations_count' => 0,
            ]);
            return $application->refresh();
        }

        $avg = $submitted->avg('weighted_score') ?? $submitted->avg('total_score');
        $application->update([
            'average_score'     => $avg ? round((float) $avg, 2) : null,
            'evaluations_count' => $submitted->count(),
        ]);

        return $application->refresh();
    }
}
