<?php namespace Mccool\LaravelArtisanBackup\Storers;

class S3Storer implements StorerInterface
{
    private $bucket;
    private $s3Path;

    public function __construct($bucket, $s3Path)
    {
        $this->bucket = $bucket;
        $this->s3Path = $s3Path;
    }

    public function setInputFile($filename);
    {
        $this->filename = $filename;
    }

    public function store()
    {
        $s3 = \AWS::get('s3');

        $s3->putObject(array(
            'Bucket'     => $this->bucket,
            'Key'        => $this->getS3DumpsPath() . '/' . $this->fileName,
            'SourceFile' => $this->filePath,
        ));
    }
}