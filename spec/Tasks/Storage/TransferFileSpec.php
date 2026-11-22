<?php

namespace spec\BackupManager\Tasks\Storage;

use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToWriteFile;
use PhpSpec\ObjectBehavior;

class TransferFileSpec extends ObjectBehavior
{
    public function it_is_initializable(FilesystemOperator $filesystem)
    {
        $this->beConstructedWith($filesystem, 'source', $filesystem, 'destination');
        $this->shouldHaveType('BackupManager\Tasks\Storage\TransferFile');
    }

    public function it_should_execute_the_transfer_file_command(FilesystemOperator $source, FilesystemOperator $destination)
    {
        $stream = fopen('php://memory', 'r');
        $source->readStream('source')->willReturn($stream);
        $destination->writeStream('destination', $stream)->shouldBeCalled();

        $this->beConstructedWith($source, 'source', $destination, 'destination');
        $this->execute();

        if (is_resource($stream)) {
            throw new \RuntimeException('Expected the source stream to be closed.');
        }
    }

    public function it_should_close_the_source_stream_when_the_write_fails(FilesystemOperator $source, FilesystemOperator $destination)
    {
        $stream = fopen('php://memory', 'r');
        $source->readStream('source')->willReturn($stream);
        $destination->writeStream('destination', $stream)->willThrow(UnableToWriteFile::atLocation('destination'));

        $this->beConstructedWith($source, 'source', $destination, 'destination');
        $this->shouldThrow(UnableToWriteFile::class)->during('execute');

        if (is_resource($stream)) {
            throw new \RuntimeException('Expected the source stream to be closed.');
        }
    }
}
