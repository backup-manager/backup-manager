<?php

use BigName\BackupManager\Manager;
use Mockery as m;

class ManagerTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $manager = new Manager('tests/config/storage.php', 'tests/config/database.php');
        $this->assertInstanceOf('BigName\BackupManager\Manager', $manager);
    }

    public function test_can_create_backup_procedure()
    {
        // these tests can't do a ton due to the fact that the manager class is a facade
        // that directly depends on a shitton of things that can't be injected due to the
        // fact that the class' very existence is to prevent the user from needing to
        $this->setExpectedException('League\Flysystem\FileNotFoundException');
        $manager = new Manager('tests/config/storage.php', 'tests/config/database.php');
        $manager->backup('null', 'null', '');
    }

    public function test_can_create_restore_procedure()
    {
        // again, I very much apologize for the nature of these tests
        $this->setExpectedException('League\Flysystem\FileNotFoundException');
        $manager = new Manager('tests/config/storage.php', 'tests/config/database.php');
        $manager->restore('null', '', 'null');
    }
}
