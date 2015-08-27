<?php

return [
    'local'       => [
        'type' => 'Local',
        'root' => '/tmp',
    ],
    's3'          => [
        'type'   => 'AwsS3',
        'key'    => '',
        'secret' => '',
        'region' => 'us-east-1',
        'version' => 'latest',
        'bucket' => '',
        'root'   => '',
    ],
    'unsupported' => [
        'type' => 'doesnt exist',
    ],
    'null'        => [
        'type' => null,
        'root' => null,
    ]
];
