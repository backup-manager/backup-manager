<?php

namespace spec\BackupManager;

use BackupManager\File;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType(File::class);
    }

    function it_retrieves_the_file_path() {
        $this->beConstructedWith('file.sql');
        $this->filePath()->shouldReturn('file.sql');
    }

    function it_trims_the_file_path() {
        $this->beConstructedWith('  file.sql  ');
        $this->filePath()->shouldReturn('file.sql');
    }

    function it_retrieves_the_root() {
        $this->beConstructedWith('file.sql', 'path/to/');
        $this->fullPath()->shouldReturn('path/to/file.sql');
    }

    function it_retrieves_the_full_path() {
        $this->beConstructedWith('file.sql', 'path/to/');
        $this->root()->shouldReturn('path/to');
    }

    function it_retrieves_the_file_extension() {
        $this->beConstructedWith('file.sql');
        $this->extension()->shouldReturn('sql');
    }

    function it_retrieves_null_when_file_has_no_extension() {
        $this->beConstructedWith('no_extension');
        $this->extension()->shouldReturn(null);
    }
}
