<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActivityReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'organizer']) ?? false;
    }

    public function rules(): array
    {
        $maxMb = (int) config('programflow.uploads.max_size_mb', 10);
        $maxKb = $maxMb * 1024;

        return [
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string', 'max:2000'],
            'content'            => ['nullable', 'string'],
            'activity_date'      => ['required', 'date'],
            'program_session_id' => ['nullable', 'integer', 'exists:program_sessions,id'],
            'status'             => ['nullable', Rule::in(['draft', 'published'])],

            'report_file'        => ['nullable', 'file', 'mimes:pdf,doc,docx', "max:$maxKb"],
            'gallery_images'     => ['nullable', 'array'],
            'gallery_images.*'   => ['file', 'image', "max:$maxKb"],
            'gallery_videos'     => ['nullable', 'array'],
            'gallery_videos.*'   => ['file', 'mimes:mp4,webm,mov', "max:".($maxKb * 5)],
        ];
    }
}
