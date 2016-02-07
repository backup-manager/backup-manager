<?php

namespace spec\BackupManager;

use BackupManager\Procedure;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProcedureSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith('name', 'master', 's3', 'file.sql', 'gzip');
    }

    function it_is_initializable() {
        $this->shouldHaveType(Procedure::class);
    }

    function it_retrieves_the_procedure_name() {
        $this->name()->shouldReturn('name');
    }

    function it_retrieves_the_database() {
        $this->database()->shouldReturn('master');
    }

    function it_retrieves_the_storage_provider() {
        $this->storageProvider()->shouldReturn('s3');
    }

    function it_retrieves_the_file_path() {
        $this->filePath()->shouldReturn('file.sql');
    }

    function it_retrieves_the_compression() {
        $this->compression()->shouldReturn('gzip');
    }
}
