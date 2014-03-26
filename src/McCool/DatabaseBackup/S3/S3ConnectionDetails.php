<?php namespace McCool\DatabaseBackup\S3; 

class S3ConnectionDetails
{
    public $key;
    public $secret;
    public $region;
    public $bucket;
    public $basePath;

    public function __construct($key, $secret, $region, $bucket, $basePath)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->region = $region;
        $this->bucket = $bucket;
        $this->basePath = $basePath;
    }
}
