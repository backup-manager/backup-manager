<?php

namespace spec\BackupManager\Tasks\Storage;

use BackupManager\Tasks\Storage\FileTransferFailed;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\BackupManager\Support\StorageDouble;

class TransferFileSpec extends ObjectBehavior
{
    public function it_is_initializable(StorageDouble $filesystem)
    {
        $this->beConstructedWith($filesystem, 'source', $filesystem, 'destination');
        $this->shouldHaveType('BackupManager\Tasks\Storage\TransferFile');
    }

    public function it_should_execute_the_transfer_file_command(StorageDouble $source, StorageDouble $destination)
    {
        $stream = fopen('php://memory', 'r');
        $source->readStream('source')->willReturn($stream);
        $destination->writeStream('destination', $stream)->shouldBeCalled()->willReturn(null);

        $this->beConstructedWith($source, 'source', $destination, 'destination');
        $this->execute();
    }

    public function it_should_close_the_source_stream(StorageDouble $source, StorageDouble $destination)
    {
        $stream = fopen('php://memory', 'r');
        $source->readStream('source')->willReturn($stream);
        $destination->writeStream('destination', $stream)->willReturn(true);

        $this->beConstructedWith($source, 'source', $destination, 'destination');
        $this->execute();

        if (is_resource($stream)) {
            throw new \RuntimeException('Expected the source stream to be closed.');
        }
    }

    public function it_should_fail_when_flysystem_v1_cannot_read_the_source(StorageDouble $source, StorageDouble $destination)
    {
        $source->readStream('source')->willReturn(false);
        $destination->writeStream(Argument::cetera())->shouldNotBeCalled();

        $this->beConstructedWith($source, 'source', $destination, 'destination');
        $this->shouldThrow(FileTransferFailed::class)->during('execute');
    }

    public function it_should_fail_when_flysystem_v1_cannot_write_the_destination(StorageDouble $source, StorageDouble $destination)
    {
        $source->readStream('source')->willReturn(fopen('php://memory', 'r'));
        $destination->writeStream('destination', Argument::any())->willReturn(false);

        $this->beConstructedWith($source, 'source', $destination, 'destination');
        $this->shouldThrow(FileTransferFailed::class)->during('execute');
    }
}
