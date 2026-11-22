<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use spec\BackupManager\Support\ExpectsAdapters;
use Prophecy\Argument;

class WebdavFilesystemSpec extends ObjectBehavior
{
    use ExpectsAdapters;

    public function it_is_initializable()
    {
        $this->shouldHaveType('BackupManager\Filesystems\WebdavFilesystem');
    }

    public function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['Webdav', 'WEBDAV', 'WeBdAv'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    public function it_should_provide_an_instance_of_an_webdav_filesystem()
    {
        $this->expectAdapter([
            'League\\Flysystem\\WebDAV\\WebDAVAdapter',
        ], $this->getConfig());
    }

    public function getConfig()
    {
        return [
            'baseUri'  => 'http://myserver.com',
            'userName' => '',
            'password' => '',
            'prefix'   => '',
        ];
    }
}
