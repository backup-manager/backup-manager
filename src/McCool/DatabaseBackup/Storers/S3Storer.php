<?php namespace McCool\DatabaseBackup\Storers;

use Aws\Common\Aws;

class S3Storer implements StorerInterface
{
    /**
     * The AWS key.
     *
     * @var string
     */
    private $awsKey;

    /**
     * The AWS secret.
     *
     * @var string
     */
    private $awsSecret;

    /**
     * The AWS region.
     *
     * @var string
     */
    private $awsRegion;

    /**
     * The AWS S3 bucket.
     *
     * @var string
     */
    private $bucket;

    /**
     * The AWS S3 path.
     *
     * @var string
     */
    private $s3Path;

    /**
     * The backup filename.
     *
     * @var string
     */
    private $filename;

    /**
     * Initialize the S3Storer instance.
     *
     * @param  string  $awsKey
     * @param  string  $awsSecret
     * @param  string  $awsRegion
     * @param  string  $bucket
     * @param  string  $s3Path
     * @return self
     */
    public function __construct($awsKey, $awsSecret, $awsRegion, $bucket, $s3Path)
    {
        $this->awsKey    = $awsKey;
        $this->awsSecret = $awsSecret;
        $this->awsRegion = $awsRegion;

        $this->bucket = $bucket;
        $this->s3Path = $s3Path;
    }

    /**
     * Sets the filename for the backup.
     *
     * @param  string  $filename
     * @return void
     */
    public function setInputFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Stores the backup to the given storage provider.
     *
     * @return void
     */
    public function store()
    {
        $this->getS3()->putObject([
            'Bucket'     => $this->bucket,
            'Key'        => $this->getS3Path() . $this->getFilename(),
            'SourceFile' => $this->filename,
            'ACL'        => 'private',
        ]);
    }

    /**
     * Returns the S3 client.
     *
     * @return \Aws\S3\S3Client
     */
    private function getS3()
    {
        return Aws::factory([
            'key'    => $this->awsKey,
            'secret' => $this->awsSecret,
            'region' => $this->awsRegion,
        ])->get('s3');
    }

    /**
     * Returns the S3 path.
     *
     * @return string
     */
    private function getS3Path()
    {
        if ( ! preg_match("/\/$/", $this->s3Path)) {
            return $this->s3Path . '/';
        }

        return $this->s3Path;
    }

    /**
     * Returns the base backup filename.
     *
     * @return string
     */
    private function getFilename()
    {
        return basename($this->filename);
    }
}