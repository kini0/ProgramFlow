<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ProgramStatus;
use App\Models\Program;
use App\Models\User;
use App\Repositories\Contracts\ProgramRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProgramService
{
    public function __construct(private ProgramRepositoryInterface $programs)
    {
    }

    /**
     * Crée un programme avec ses champs de candidature et critères par défaut.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, User $creator): Program
    {
        return DB::transaction(function () use ($data, $creator) {
            $data['created_by'] = $creator->id;
            $data['status']     = $data['status'] ?? ProgramStatus::Draft->value;

            /** @var Program $program */
            $program = $this->programs->create($data);

            $this->seedDefaultFields($program);
            $this->seedDefaultCriteria($program);

            return $program;
        });
    }

    public function update(Program $program, array $data): Program
    {
        return $this->programs->update($program, $data);
    }

    public function changeStatus(Program $program, ProgramStatus $status): Program
    {
        return $this->programs->update($program, ['status' => $status->value]);
    }

    public function archive(Program $program): Program
    {
        return $this->changeStatus($program, ProgramStatus::Archived);
    }

    /**
     * Champs par défaut générés à la création d'un programme.
     */
    protected function seedDefaultFields(Program $program): void
    {
        $defaults = [
            ['section' => 'parcours',   'label' => 'Parcours académique', 'key' => 'parcours_academique', 'type' => 'textarea', 'is_required' => true],
            ['section' => 'parcours',   'label' => 'Expérience professionnelle', 'key' => 'experience_pro', 'type' => 'textarea', 'is_required' => false],
            ['section' => 'projet',     'label' => 'Description de votre projet', 'key' => 'projet_description', 'type' => 'textarea', 'is_required' => true],
            ['section' => 'projet',     'label' => 'Impact attendu', 'key' => 'projet_impact', 'type' => 'textarea', 'is_required' => false],
            ['section' => 'motivation', 'label' => 'Lettre de motivation', 'key' => 'lettre_motivation', 'type' => 'textarea', 'is_required' => true],
            ['section' => 'documents',  'label' => 'CV (PDF)', 'key' => 'cv', 'type' => 'file', 'is_required' => true,
                'validation_rules' => ['mimes:pdf,doc,docx', 'max:10240']],
            ['section' => 'documents',  'label' => 'Pièce d\'identité', 'key' => 'piece_identite', 'type' => 'file', 'is_required' => false,
                'validation_rules' => ['mimes:pdf,jpg,png', 'max:5120']],
        ];

        foreach ($defaults as $i => $field) {
            $program->applicationFields()->create(array_merge($field, ['order_column' => $i]));
        }
    }

    protected function seedDefaultCriteria(Program $program): void
    {
        $defaults = [
            ['label' => 'Pertinence du parcours', 'weight' => 2, 'max_score' => 20],
            ['label' => 'Qualité du projet',      'weight' => 3, 'max_score' => 20],
            ['label' => 'Impact attendu',         'weight' => 3, 'max_score' => 20],
            ['label' => 'Motivation et engagement', 'weight' => 2, 'max_score' => 20],
        ];

        foreach ($defaults as $i => $c) {
            $program->evaluationCriteria()->create(array_merge($c, ['order_column' => $i]));
        }
    }

    public function dashboardStats(): array
    {
        return $this->programs->statsForDashboard();
    }
}
