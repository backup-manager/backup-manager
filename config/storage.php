<?php

return [
    'local' => [
        'type' => 'Local',
        'root' => base_path('/storage/app/backups'),
    ],
    's3' => [
        'type' => 'AwsS3',
        'key'    => 'AKIAIUEU5LHWIDZNOBCA',
        'secret' => '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',
        'region' => 'us-east-1',
        'bucket' => 'cmosguy-test',
        'root'   => '',
    ],
    'rackspace' => [
        'type' => 'Rackspace',
        'username' => '',
        'key' => '',
        'container' => '',
        'zone' => '',
        'endpoint' => 'https://identity.api.rackspacecloud.com/v2.0/',
        'root' => '',
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
        'port' => 21,
        'passive' => true,
        'ssl' => true,
        'timeout' => 30,
        'root' => '',
    ],
    'sftp' => [
        'type' => 'Sftp',
        'host' => '',
        'username' => '',
        'password' => '',
        'port' => 21,
        'timeout' => 10,
        'privateKey' => '',
        'root' => '',
    ],
];
