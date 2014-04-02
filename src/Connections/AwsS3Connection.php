<?php namespace BigName\DatabaseBackup\Connections;

use Aws\Common\Enum\Region;

class AwsS3Connection
{
    /**
     * @var string
     */
    public $key;
    /**
     * @var string
     */
    public $secret;
    /**
     * @var string
     */
    public $region;

    public function __construct($key, $secret, $region = Region::US_EAST_1)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->region = $region;
    }
} 
