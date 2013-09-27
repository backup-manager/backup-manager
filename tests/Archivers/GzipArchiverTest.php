<?php

use Mockery as m;

class GzipArchiverTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testGzipArchiver()
    {
        $processor = m::mock('McCool\DatabaseBackup\Processors\ProcessorInterface');
        $processor->shouldReceive('process')->with('gzip test.sql');
        $processor->shouldReceive('getErrors')->andReturn(null);

        $archiver  = new \McCool\DatabaseBackup\Archivers\GzipArchiver($processor);

        $archiver->setInputFilename('test.sql');
        $archiver->archive();

        $this->assertEquals('test.sql.gz', $archiver->getOutputFilename());
    }

    /**
     * @expectedException McCool\DatabaseBackup\Processors\ProcessorException
     */
    public function testThrowsExceptionOnError()
    {
        $processor = m::mock('McCool\DatabaseBackup\Processors\ProcessorInterface');
        $processor->shouldReceive('process');
        $processor->shouldReceive('getErrors')->andReturn(true);

        $archiver  = new \McCool\DatabaseBackup\Archivers\GzipArchiver($processor);
        $archiver->archive();
    }
}