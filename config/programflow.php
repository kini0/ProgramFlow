<?php

/**
 * Configuration spécifique à ProgramFlow.
 *
 * Centralise les paramètres métier de l'application afin de pouvoir les
 * surcharger via le fichier .env sans toucher au code.
 */
return [
    'foundation_name' => env('PROGRAMFLOW_FOUNDATION_NAME', 'Fondation Bénianh'),
    'default_program' => env('PROGRAMFLOW_DEFAULT_PROGRAM', 'Leadership Féminin'),

    'uploads' => [
        'max_size_mb'        => (int) env('PROGRAMFLOW_MAX_UPLOAD_MB', 10),
        'allowed_mimes'      => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
        'allowed_video_mimes' => ['mp4', 'webm', 'mov'],
    ],

    'evaluation' => [
        'default_scale'       => 20,
        'auto_lock_after_days' => 30,
    ],

    'application' => [
        'draft_max_age_days'  => 60,
        'cooldown_minutes'    => 5,
    ],
];
