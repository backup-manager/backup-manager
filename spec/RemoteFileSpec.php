<?php

namespace spec\BackupManager;

use BackupManager\File;
use BackupManager\RemoteFile;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RemoteFileSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith('local', new File('path/to/file.sql'));
    }

    function it_is_initializable() {
        $this->shouldHaveType(RemoteFile::class);
    }

    function it_retrieves_the_provider() {
        $this->provider()->shouldReturn('local');
    }

    function it_retrieves_the_path() {
        $this->path()->shouldReturn('path/to/file.sql');
    }

    function it_retrieves_the_file() {
        $this->file()->shouldBeAnInstanceOf(File::class);
    }
}
