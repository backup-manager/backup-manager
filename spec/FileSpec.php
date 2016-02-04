<?php

namespace spec\BackupManager;

use BackupManager\File;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType(File::class);
    }

    function it_retrieves_the_path() {
        $this->beConstructedWith('path/to/file.sql');
        $this->path()->shouldReturn('path/to/file.sql');
    }

    function it_trims_the_path() {
        $this->beConstructedWith('  path/to/file.sql  ');
        $this->path()->shouldReturn('path/to/file.sql');
    }
}
