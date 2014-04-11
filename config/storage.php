<?php

return [
    'local' => [
        'type' => 'Local',
        'working-path' => '/',
    ],
    's3' => [
        'type' => 'AwsS3',
        'key'    => '',
        'secret' => '',
        'region' => Aws\Common\Enum\Region::US_EAST_1,
        'bucket' => '',
    ],
    'rackspace' => [
        'type' => 'Rackspace',
        'username' => '',
        'password' => '',
        'container' => '',
    ],
    'dropbox' => [
        'type' => 'Dropbox',
        'token' => '',
        'key' => '',
        'secret' => '',
        'app' => '',
        'root' => '',
    ],
    'ftp' => [
        'type' => 'Ftp',
        'host' => '',
        'username' => '',
        'password' => '',
        'root' => '',
        'port' => 21,
        'passive' => true,
        'ssl' => true,
        'timeout' => 30,
    ],
];
