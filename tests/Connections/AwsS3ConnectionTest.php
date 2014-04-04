<?php

use BigName\DatabaseBackup\Connections\AwsS3Connection;
use Mockery as m;

class AwsS3ConnectionTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test()
    {
        $s3 = new AwsS3Connection('key', 'secret', 'region');

        $this->assertInstanceOf('BigName\DatabaseBackup\Connections\AwsS3Connection', $s3);
        $this->assertEquals('key', $s3->key);
        $this->assertEquals('secret', $s3->secret);
        $this->assertEquals('region', $s3->region);
    }
}
