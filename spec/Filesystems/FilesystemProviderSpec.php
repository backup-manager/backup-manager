<?php

namespace spec\BackupManager\Filesystems;

use BackupManager\Config\Config;
use BackupManager\Filesystems\LocalFilesystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FilesystemProviderSpec extends ObjectBehavior
{
    public function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith(Config::fromPhpFile('spec/configs/storage.php'));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('BackupManager\Filesystems\FilesystemProvider');
    }

    public function it_should_provide_requested_filesystems_by_their_names()
    {
        $this->add(new LocalFilesystem);
        $this->get('local')->shouldHaveType('League\Flysystem\Filesystem');
    }

    public function it_should_throw_an_exception_if_a_filesystem_is_unsupported()
    {
        $this->shouldThrow('BackupManager\Filesystems\FilesystemTypeNotSupported')->during('get', ['unsupported']);
    }

    public function it_should_provide_a_list_of_all_available_providers()
    {
        $this->getAvailableProviders()->shouldBe(['local', 's3', 'unsupported', 'null']);
    }

    public function it_should_provide_requested_filesystem_configuration_data()
    {
        $this->getConfig('local', 'type')->shouldBe('Local');
    }
}
