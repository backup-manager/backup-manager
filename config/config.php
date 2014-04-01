<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | AWS Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can add your AWS credentials so the database backup can store
    | the backup file on your AWS hosting.
    |
    | You can change the region global to a different one by choosing from the
    | list provided in the Aws\Common\Enum\Region class.
    |
    */

    'aws' => [
        'key'    => '',
        'secret' => '',
        'region' => Aws\Common\Enum\Region::US_EAST_1,
    ],
];