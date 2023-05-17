<?php

// config for Comes/SimpleAuthenticator
return [
    'secrets' => [
        // 'another' => env('ANOTHER_SECRET'),
        // it is not recommended to store secrets in this config file
        'sample' => env('SAMPLE_SECRET', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'),
    ],
];
