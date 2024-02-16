<?php

return [
    'encryption' => [
        'cipher'    => 'aes-256-ctr',
        'hash'      => 'sha256',
        'encoding'  => 'base64'
    ],
    'screenplay' => [
        'defaults' => [
            'guid'    => 'rfc4122',
            'lang'    => 'en',
            'locale'  => 'en_GB',
            'charset' => 'utf8',
            'dir'     => 'ltr'
        ]
    ]
];