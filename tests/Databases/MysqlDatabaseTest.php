<?php

use Mockery as m;
use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Databases\MysqlDatabase;

class MysqlDatabaseTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function test_can_create()
    {
        $mysql = $this->getDatabase();
        $this->assertInstanceOf('BigName\BackupManager\Databases\MysqlDatabase', $mysql);
    }

    public function test_handles_correct_types()
    {
        $db = $this->getDatabase();

        foreach (['mysql', 'MYSQL', 'MySQL'] as $type) {
            $this->assertTrue($db->handles($type));
        }

        foreach ([null, 'foo'] as $type) {
            $this->assertFalse($db->handles($type));
        }
    }

    public function test_get_dump_command()
    {
        $config = Config::fromPhpFile('tests/config/database.php');
        $mysql = $this->getDatabase();
        $mysql->setConfig($config->get('development'));
        $this->assertEquals("mysqldump --host='foo' --port='3306' --user='bar' --password='baz' 'test' > 'outputPath'", $mysql->getDumpCommandLine('outputPath'));
    }

    public function test_get_restore_command()
    {
        $config = Config::fromPhpFile('tests/config/database.php');
        $mysql = $this->getDatabase();
        $mysql->setConfig($config->get('development'));
        $this->assertEquals("mysql --host='foo' --port='3306' --user='bar' --password='baz' 'test' -e \"source outputPath;\"", $mysql->getRestoreCommandLine('outputPath'));
    }

    /**
     * @return MysqlDatabase
     */
    private function getDatabase()
    {
        $database = new MysqlDatabase;
        return $database;
    }
}
