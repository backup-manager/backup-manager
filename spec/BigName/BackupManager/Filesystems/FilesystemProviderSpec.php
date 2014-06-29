<?php

namespace spec\BigName\BackupManager\Filesystems;

use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Filesystems\LocalFilesystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FilesystemProviderSpec extends ObjectBehavior
{
    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith(Config::fromPhpFile('tests/unit/config/storage.php'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\BackupManager\Filesystems\FilesystemProvider');
    }

    function it_should_deliver_filesystem_by_name()
    {
        $this->add(new LocalFilesystem);
        $this->get('local')->shouldHaveType('League\Flysystem\Filesystem');
    }

    function it_should_throw_an_exception_if_it_cant_find_the_filesystem()
    {
        $this->shouldThrow('BigName\BackupManager\Filesystems\FilesystemTypeNotSupported')->during('get', ['unsupported']);
    }

    function it_should_return_all_available_providers()
    {
        $this->getAvailableProviders()->shouldBe(['local', 's3', 'unsupported', 'null']);
    }

    function it_should_deliver_filesystem_configuration_data()
    {
        $this->getConfig('local', 'type')->shouldBe('Local');
    }
}
