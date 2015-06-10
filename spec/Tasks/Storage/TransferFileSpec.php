<?php

namespace spec\BackupManager\Tasks\Storage;

use League\Flysystem\Filesystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransferFileSpec extends ObjectBehavior {

    function it_is_initializable(Filesystem $filesystem) {
        $this->beConstructedWith($filesystem, 'source', $filesystem, 'destination');
        $this->shouldHaveType('BackupManager\Tasks\Storage\TransferFile');
    }

    function it_should_execute_the_transfer_file_command(Filesystem $source, Filesystem $destination) {
        $source->readStream('source')->willReturn('data');
        $destination->writeStream('destination', 'data')->shouldBeCalled();

        $this->beConstructedWith($source, 'source', $destination, 'destination');
        $this->execute();
    }
}
