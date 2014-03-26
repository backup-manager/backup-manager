<?php namespace McCool\DatabaseBackup\S3;

use Aws\Common\Aws;
use McCool\DatabaseBackup\Downloaders\DownloaderInterface;
use McCool\DatabaseBackup\StorerInterface;

/**
 * Class S3
 * @package McCool\DatabaseBackup\S3
 */
class S3 implements StorerInterface, DownloaderInterface
{
    /**
     * @var S3ConnectionDetails
     */
    private $s3ConnectionDetails;
    /**
     * The AWS S3 client instance.
     *
     * @var string
     */
    protected $s3Client;
    /**
     * The backup filename.
     *
     * @var string
     */
    protected $filename;
    /**
     * @var Aws
     */
    private $aws;

    /**
     * @param Aws $aws
     * @param S3ConnectionDetails $s3ConnectionDetails
     */
    public function __construct(Aws $aws, S3ConnectionDetails $s3ConnectionDetails)
    {
        $this->s3ConnectionDetails = $s3ConnectionDetails;
        $this->aws = $aws;
    }

    /**
     * Stores the backup to the given storage provider.
     * @param $filename
     * @return void
     */
    public function store($filename)
    {
        $this->getS3Client()->putObject([
            'Bucket'     => $this->bucket,
            'Key'        => $this->getS3Path() . $this->getFilename(),
            'SourceFile' => $filename,
            'ACL'        => 'private',
        ]);
    }

    /**
     * @param $filePath
     * @param $localStorageDirectory
     * @return null
     */
    public function download($filePath, $localStorageDirectory)
    {
        $fileData = $this->getS3Client()->getObject([
            'Bucket' => $this->bucket,
            'Key' => $filePath,
        ]);

        $path = pathinfo($filePath);

        file_put_contents($localStorageDirectory . '/' . $path['filename'], $fileData);
    }

    /**
     * @return string
     */
    private function getS3Client()
    {
        if ( ! $this->s3Client) {
            $this->s3Client = $this->aws->factory([
                'key'    => $this->s3ConnectionDetails->key,
                'secret' => $this->s3ConnectionDetails->secret,
                'region' => $this->s3ConnectionDetails->region,
            ])->get('s3');
        }
        return $this->s3Client;
    }

    /**
     * Returns the S3 path.
     * @return string
     */
    private function getS3Path()
    {
        if ( ! preg_match('/\/$/', $this->s3Path)) {
            return $this->s3Path . '/';
        }

        return $this->s3Path;
    }

    /**
     * Returns the base backup filename.
     * @return string
     */
    private function getFilename()
    {
        return basename($this->filename);
    }
}
