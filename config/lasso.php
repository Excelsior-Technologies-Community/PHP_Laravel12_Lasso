<?php

declare(strict_types=1);

return [

    'compiler' => [
        'script' => 'npm run build',
        'timeout' => 600,
        'output' => 'progress',
        'excluded_files' => [],
        'excluded_directories' => [],
    ],

    'storage' => [
        'disk' => 'local', // MUST match disk in filesystems.php
        'upload_to' => 'lasso',
        'environment' => env('LASSO_ENV', null),
        'prefix' => env('LASSO_PREFIX', ''),
        'max_bundles' => 5,
    ],

    'webhooks' => [
        'publish' => [],
        'pull' => [],
    ],

    'public_path' => public_path(),
];