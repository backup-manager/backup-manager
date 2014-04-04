<?php

return [
    'local' => [
        'type' => 'Local',
        'working-path' => '/',
    ],
    's3' => [
        'type' => 'AwsS3',
        'key' => '',
        'secret' => '',
        'region' => Aws\Common\Enum\Region::US_EAST_1,
        'bucket' => '',
    ],
    'unsupported' => [
        'type' => 'doesnt exist',
    ],
];
