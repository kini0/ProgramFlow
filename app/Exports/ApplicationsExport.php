<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Program;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplicationsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(public Program $program)
    {
    }

    public function collection()
    {
        return $this->program->applications()->with('candidate')->get();
    }

    public function headings(): array
    {
        return ['Référence', 'Nom', 'Prénom', 'Email', 'Statut', 'Score moyen', 'Date soumission'];
    }

    public function map($application): array
    {
        return [
            $application->reference,
            $application->candidate?->last_name,
            $application->candidate?->first_name,
            $application->candidate?->email,
            $application->status->label(),
            $application->average_score,
            $application->submitted_at?->format('Y-m-d H:i'),
        ];
    }
}
