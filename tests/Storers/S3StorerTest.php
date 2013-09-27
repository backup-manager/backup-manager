<?php

use Mockery as m;

class S3StorerTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testS3Storer()
    {
        $s3Client = m::mock();
        $s3Client->shouldReceive('putObject')->with([
            'Bucket'     => 'bucket',
            'Key'        => 'path/iceking.sql.gz',
            'SourceFile' => 'backups/iceking.sql.gz',
            'ACL'        => 'private',
        ]);

        $storer = new \McCool\DatabaseBackup\Storers\S3Storer($s3Client, 'bucket', 'path');
        $storer->setInputFilename('backups/iceking.sql.gz');
        $storer->store();
    }
}