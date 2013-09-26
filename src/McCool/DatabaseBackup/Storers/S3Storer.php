<?php namespace McCool\DatabaseBackup\Storers;

use Aws\Common\Aws;

class S3Storer implements StorerInterface
{
    private $awsKey;
    private $awsSecret;
    private $awsRegion;

    private $bucket;
    private $s3Path;

    private $filename;

    public function __construct($awsKey, $awsSecret, $awsRegion, $bucket, $s3Path)
    {
        $this->awsKey    = $awsKey;
        $this->awsSecret = $awsSecret;
        $this->awsRegion = $awsRegion;

        $this->bucket = $bucket;
        $this->s3Path = $s3Path;
    }

    public function setInputFilename($filename)
    {
        $this->filename = $filename;
    }

    public function store()
    {
        $this->getS3()->putObject([
            'Bucket'     => $this->bucket,
            'Key'        => $this->getS3Path() . $this->getFilename(),
            'SourceFile' => $this->filename,
            'ACL'        => 'private',
        ]);
    }

    private function getS3()
    {
        return Aws::factory([
            $this->awsKey,
            $this->awsSecret,
            $this->awsRegion,
        ])->get('s3');
    }

    private function getS3Path()
    {
        if ( ! preg_match("/\/$/", $this->s3Path)) {
            return $this->s3Path . '/';
        }

        return $this->s3Path;
    }

    private function getFilename()
    {
        return basename($this->filename);
    }
}