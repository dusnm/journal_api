<?php

use App\Services\DiskImageUploadService;
use App\Services\S3ImageUploadService;

return [
    'local' => [
        'driver' => DiskImageUploadService::class
    ],
    's3' => [
        'driver' => S3ImageUploadService::class
    ]
];
