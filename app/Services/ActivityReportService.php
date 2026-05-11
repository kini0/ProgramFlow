<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ActivityReportStatus;
use App\Models\ActivityReport;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ActivityReportService
{
    /**
     * Crée un rapport d'activité avec ses médias (fichier principal,
     * galerie d'images, vidéos).
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, UploadedFile|UploadedFile[]>  $files
     */
    public function create(array $data, Program $program, User $author, array $files = []): ActivityReport
    {
        return DB::transaction(function () use ($data, $program, $author, $files) {
            $status = $data['status'] ?? ActivityReportStatus::Draft->value;

            /** @var ActivityReport $report */
            $report = $program->activityReports()->create([
                'program_id'         => $program->id,
                'program_session_id' => $data['program_session_id'] ?? null,
                'title'              => $data['title'],
                'description'        => $data['description'] ?? null,
                'content'            => $data['content'] ?? null,
                'activity_date'      => $data['activity_date'],
                'status'             => $status,
                'published_at'       => $status === ActivityReportStatus::Published->value ? now() : null,
                'created_by'         => $author->id,
            ]);

            $this->attachMedia($report, $files);

            return $report->refresh();
        });
    }

    public function update(ActivityReport $report, array $data, array $files = []): ActivityReport
    {
        return DB::transaction(function () use ($report, $data, $files) {
            $report->update([
                'title'              => $data['title'] ?? $report->title,
                'description'        => $data['description'] ?? $report->description,
                'content'            => $data['content'] ?? $report->content,
                'activity_date'      => $data['activity_date'] ?? $report->activity_date,
                'program_session_id' => $data['program_session_id'] ?? $report->program_session_id,
            ]);

            $this->attachMedia($report, $files);

            return $report->refresh();
        });
    }

    public function publish(ActivityReport $report): ActivityReport
    {
        $report->update([
            'status'       => ActivityReportStatus::Published->value,
            'published_at' => now(),
        ]);
        return $report;
    }

    public function unpublish(ActivityReport $report): ActivityReport
    {
        $report->update([
            'status'       => ActivityReportStatus::Draft->value,
            'published_at' => null,
        ]);
        return $report;
    }

    /**
     * Attache des fichiers au rapport selon 3 catégories :
     *   - report_file (un seul, le PDF principal)
     *   - gallery_image (multi)
     *   - gallery_video (multi)
     */
    protected function attachMedia(ActivityReport $report, array $files): void
    {
        // Fichier principal
        if (!empty($files['report_file']) && $files['report_file'] instanceof UploadedFile) {
            $this->storeDocument($report, $files['report_file'], 'report_file');
        }

        // Galerie images (multi-upload via files['gallery_images'][])
        $images = $files['gallery_images'] ?? [];
        if ($images && !is_array($images)) {
            $images = [$images];
        }
        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $this->storeDocument($report, $image, 'gallery_image', 'public');
            }
        }

        // Vidéos
        $videos = $files['gallery_videos'] ?? [];
        if ($videos && !is_array($videos)) {
            $videos = [$videos];
        }
        foreach ($videos as $video) {
            if ($video instanceof UploadedFile) {
                $this->storeDocument($report, $video, 'gallery_video', 'public');
            }
        }
    }

    protected function storeDocument(
        ActivityReport $report,
        UploadedFile $file,
        string $category,
        string $disk = 'public',
    ): void {
        $path = $file->store('activity-reports/'.$report->id.'/'.$category, $disk);

        $report->documents()->create([
            'label'         => $report->title,
            'original_name' => $file->getClientOriginalName(),
            'disk'          => $disk,
            'path'          => $path,
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'category'      => $category,
            'uploaded_by'   => $report->created_by,
        ]);
    }
}
