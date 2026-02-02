<?php

return [
    'base_path' => base_path(),
    'output_path' => storage_path('file-integrity-checksum.json'),
    'exclude_paths' => [
        '.env',
        '.github/',
        'node_modules/',
        'vendor/',
        'public/storage/',
        'storage/',
        'bootstrap/cache/',
        'file-integrity-checksum.json',
        'file-integrity-checksum-remote.json',
        '.htpasswd',
        'public/sitemap.xml',
        'REVISION',
    ],
];
