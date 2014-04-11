<?php

use BigName\BackupManager\Filesystems\FtpFilesystem;
use Mockery as m;

class FtpFilesystemTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $ftp = new FtpFilesystem();
        $this->assertInstanceOf('BigName\BackupManager\Filesystems\FtpFilesystem', $ftp);
    }

    public function test_get_correct_filesystem()
    {
        $ftp = new FtpFilesystem();
        $filesystem = $ftp->get([
            'host' => 'ftp.example.com',
            'username' => 'example.com',
            'password' => 'password',
        ]);
        $this->assertInstanceOf('League\Flysystem\Adapter\Ftp', $filesystem->getAdapter());
    }
}
