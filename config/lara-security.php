<?php

use DualityStudio\LaraSecurity\{Directives, Headers};

return [
    'enabled' => env('LARA_SECURITY_ENABLED', true),

    'uses_vite' => true,

    'headers' => [
        Headers::CONTENT_SECURITY_POLICY => [
            Directives::BASE => [
                Directives::SOURCE_SELF,
            ],
            Directives::DEFAULT => [
                Directives::SOURCE_VITE_ASSET,
            ],
            Directives::SCRIPT => [
                Directives::SOURCE_VITE_ASSET,
            ],
            Directives::OBJECT => [
                Directives::SOURCE_NONE,
            ],
            Directives::UPGRADE_INSECURE_REQUESTS => [],
        ],

        Headers::PERMISSIONS_POLICY => 'accelerometer=(), camera=(), geolocation=(), magnetometer=(), microphone=(), payment=(), usb=()',

        Headers::REFERRER_POLICY => 'same-origin',

        Headers::STRICT_TRANSPORT_SECURITY => 'max-age=31536000; includeSubDomains; preload',

        Headers::X_CONTENT_TYPE_OPTIONS => 'nosniff',

        Headers::X_FRAME_OPTIONS => 'SAMEORIGIN',

        Headers::X_XSS_PROTECTION => '1; mode=block',
    ],
];
