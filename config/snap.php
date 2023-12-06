<?php

return [
    'providers' => [
        'bca' => [
            'host' => env('SNAP_BCA_HOST', 'https://devapi.klikbca.com'),
            'client_key' => env('SNAP_BCA_CLIENT_KEY'),
            'client_secret' => env('SNAP_BCA_CLIENT_SECRET'),
            'partner_id' => env('SNAP_BCA_PARTNER_ID'),
            'private_key' => env('SNAP_BCA_PRIVATE_KEY'),
            'public_key' => env('SNAP_BCA_PUBLIC_KEY'),
            'channel_id' => '95051',
            'api_prefix' => '/openapi',
            'log_channel' => 'bca',
        ],
    ],
];
