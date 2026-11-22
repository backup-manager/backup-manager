<?php

return [
    'local' => [
        'type' => 'Local',
        'root' => '/',
    ],
    's3' => [
        'type' => 'AwsS3',
        'key'    => '',
        'secret' => '',
        'region' => 'us-east-1',
        'version' => 'latest',
        'bucket' => '',
        'root'   => '',
        'use_path_style_endpoint' => false,
    ],
    'gcs' => [
        'type' => 'Gcs',
        'projectId' => '',
        'keyFilePath' => '',
        'bucket' => '',
        'root'   => '',
    ],
    'dropbox' => [
        'type' => 'Dropbox',
        'token' => '',
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
    'sftp' => [
        'type' => 'Sftp',
        'host' => '',
        'username' => '',
        'password' => '',
        'root' => '',
        'port' => 22,
        'timeout' => 10,
        'privateKey' => '',
    ],
    'webdav' => [
        'type' => 'Webdav',
        'baseUri' => 'http://myserver.com',
        'userName' => '',
        'password' => '',
        'prefix' => '',
    ],
];
