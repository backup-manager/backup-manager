<?php

return [
    'development' => [
        'type'     => 'mysql',
        'host'     => 'foo',
        'port'     => '3306',
        'user'     => 'bar',
        'pass'     => 'baz',
        'database' => 'test',
    ],
    'production'  => [
        'type'     => 'postgresql',
        'host'     => 'foo',
        'port'     => '3306',
        'user'     => 'bar',
        'pass'     => 'baz',
        'database' => 'test',
    ],
    'unsupported' => [
        'type' => 'doesnt exist',
    ],
    'null'        => [
        'type' => null,
    ],
];
