<?php

use BigName\BackupManager\Compressors\CompressorProvider;
use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\Manager;
use Mockery as m;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    private $manager;

    protected function setUp()
    {
        $filesystems = new FilesystemProvider(Config::fromPhpFile('tests/config/storage.php'));
        $databases = new DatabaseProvider(Config::fromPhpFile('tests/config/database.php'));
        $this->manager = new Manager($filesystems, $databases, new CompressorProvider(), '');
    }

    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $this->assertInstanceOf('BigName\BackupManager\Manager', $this->manager);
    }

    public function test_can_create_backup_procedure()
    {
        $backup = $this->manager->makeBackup();
        $this->assertInstanceOf('BigName\BackupManager\Procedures\BackupProcedure', $backup);
    }

    public function test_can_create_restore_procedure()
    {
        $restore = $this->manager->makeRestore();
        $this->assertInstanceOf('BigName\BackupManager\Procedures\RestoreProcedure', $restore);
    }
}
