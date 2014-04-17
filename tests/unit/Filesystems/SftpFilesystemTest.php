<?php

use BigName\BackupManager\Filesystems\SftpFilesystem;
use Mockery as m;

class SftpFilesystemTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $ftp = new SftpFilesystem();
        $this->assertInstanceOf('BigName\BackupManager\Filesystems\SftpFilesystem', $ftp);
    }

    public function test_handles_correct_types()
    {
        $fs = new SftpFilesystem();

        foreach (['sftp', 'SFTP', 'SFtp'] as $type) {
            $this->assertTrue($fs->handles($type));
        }

        foreach ([null, 'foo'] as $type) {
            $this->assertFalse($fs->handles($type));
        }
    }

    public function test_get_correct_filesystem()
    {
        $sftp = new SftpFilesystem();
        $filesystem = $sftp->get([
            'host' => 'sftp.example.com',
            'username' => 'example.com',
            'password' => 'password',
            'root' => '/path/to/root',
            'port' => 21,
            'timeout' => 10,
            'privateKey' => '~/.ssh/private_key',
        ]);
        $this->assertInstanceOf('League\Flysystem\Adapter\Sftp', $filesystem->getAdapter());
    }
}
