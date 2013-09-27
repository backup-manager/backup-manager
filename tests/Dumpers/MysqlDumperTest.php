<?php

use Mockery as m;

class MysqlDumperTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testMysqlDumper()
    {
        $processor = m::mock('McCool\DatabaseBackup\Processors\ProcessorInterface');
        $processor->shouldReceive('process')->with("mysqldump --host='localhost' --port='3306' --user='username' --password='password' 'db_name' > 'output.sql'");
        $processor->shouldReceive('getErrors')->andReturn(null);

        $dumper = new \McCool\DatabaseBackup\Dumpers\MysqlDumper($processor, 'localhost', 3306, 'username', 'password', 'db_name', 'output.sql');

        $dumper->dump();

        $this->assertEquals('output.sql', $dumper->getOutputFilename());
    }
}