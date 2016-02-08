<?php

namespace spec\BackupManager;

use BackupManager\File;
use BackupManager\Procedure;
use BackupManager\RemoteFile;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProcedureSpec extends ObjectBehavior {

    function let() {
        $this->beConstructedWith('name', 'master', ['s3' => 'file.sql'], 'gzip');
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

    function it_retrieves_the_destinations() {
        $this->destinations()->shouldBeLike([new RemoteFile('s3', new File('file.sql'))]);
    }

    function it_retrieves_the_compression() {
        $this->compression()->shouldReturn('gzip');
    }
}
