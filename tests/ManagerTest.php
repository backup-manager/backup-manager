<?php

use BigName\BackupManager\Manager;
use Mockery as m;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    private $manager;

    protected function setUp()
    {
        $this->manager = new Manager('tests/config/storage.php', 'tests/config/database.php');
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
