<?php

namespace spec\BigName\BackupManager\Procedures;

use BigName\BackupManager\Compressors\Compressor;
use BigName\BackupManager\Compressors\CompressorProvider;
use BigName\BackupManager\Databases\Database;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\Procedures\Sequence;
use BigName\BackupManager\ShellProcessing\ShellProcessor;
use League\Flysystem\Filesystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class BackupProcedureSpec
 *
 * This test could use some love
 *
 * @package spec\BigName\BackupManager\Procedures
 */
class BackupProcedureSpec extends ObjectBehavior
{
    function it_is_initializable(FilesystemProvider $filesystemProvider, DatabaseProvider $databaseProvider, CompressorProvider $compressorProvider, ShellProcessor $shellProcessor, Sequence $sequence)
    {
        $this->beConstructedWith($filesystemProvider, $databaseProvider, $compressorProvider, $shellProcessor, $sequence);
        $this->shouldHaveType('BigName\BackupManager\Procedures\BackupProcedure');
    }

    function it_should_build_the_correct_sequence(
        FilesystemProvider $filesystemProvider,
        DatabaseProvider $databaseProvider,
        CompressorProvider $compressorProvider,
        ShellProcessor $shellProcessor,
        Database $database,
        Filesystem $filesystem,
        Compressor $compressor,
        Sequence $sequence)
    {
        $sequence->add(Argument::any())->shouldBeCalled();
        $sequence->execute()->shouldBeCalled();

        $filesystemProvider->get('destinationType')->willReturn($filesystem);
        $filesystemProvider->get('local')->willReturn($filesystem);
        $filesystemProvider->getConfig('local', 'root')->willReturn();
        $databaseProvider->get('databaseName')->willReturn($database);
        $compressorProvider->get('gzip')->willReturn($compressor);

        $this->beConstructedWith($filesystemProvider, $databaseProvider, $compressorProvider, $shellProcessor, $sequence);

        $this->run('databaseName', 'destinationType', 'destinationPath', 'gzip');
    }
}
