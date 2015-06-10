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
        'region' => Aws\Common\Enum\Region::US_EAST_1,
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
