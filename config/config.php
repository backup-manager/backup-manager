<?php

return [
    's3' => [
        'type' => 'AwsS3',
        'key'    => '',
        'secret' => '',
        'region' => Aws\Common\Enum\Region::US_EAST_1,
        'bucket' => '',
    ],
    'development' => [
        'type' => 'mysql',
        'host' => '',
        'port' => '3306',
        'user' => '',
        'pass' => '',
        'database' => '',
    ],
];
