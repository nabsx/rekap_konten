<?php

return [
    'available' => [
        'instagram' => [
            'name'          => 'Instagram',
            'icon'          => 'bi-instagram',
            'color'         => '#E1306C',
            'content_types' => ['reels', 'post'],
            'metrics'       => ['followers', 'viewers'],
        ],
        'tiktok' => [
            'name'          => 'TikTok',
            'icon'          => 'bi-tiktok',
            'color'         => '#000000',
            'content_types' => null,
            'metrics'       => ['followers', 'viewers'],
        ],
        'x' => [
            'name'          => 'X (Twitter)',
            'icon'          => 'bi-twitter-x',
            'color'         => '#000000',
            'content_types' => null,
            'metrics'       => ['followers', 'viewers'],
        ],
        'facebook' => [
            'name'          => 'Facebook',
            'icon'          => 'bi-facebook',
            'color'         => '#1877F2',
            'content_types' => null,
            'metrics'       => ['followers', 'viewers'],
        ],
        'youtube' => [
            'name'          => 'YouTube',
            'icon'          => 'bi-youtube',
            'color'         => '#FF0000',
            'content_types' => null,
            'metrics'       => ['viewers', 'subscribers'],
        ],
        'website' => [
            'name'          => 'Website',
            'icon'          => 'bi-globe',
            'color'         => '#0d6efd',
            'content_types' => null,
            'metrics'       => [],
        ],
    ],

    'content_types' => [
        'reels' => 'Reels',
        'post'  => 'Post',
        'video' => 'Video',
        'shorts' => 'Shorts',
    ],

    // Platform-specific rules
    'requires_content_type' => ['instagram'],
    'supports_followers'    => ['instagram', 'tiktok', 'x', 'facebook'],
    'supports_viewers'      => ['instagram', 'tiktok', 'x', 'facebook', 'youtube'],
    'supports_subscribers'  => ['youtube'],
];
