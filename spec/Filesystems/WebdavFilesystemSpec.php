<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WebdavFilesystemSpec extends ObjectBehavior
{
    public function let(): void
    {
        if (!class_exists('League\Flysystem\WebDAV\WebDAVAdapter')) {
            throw new SkippingException('Requires Flysystem WebDAVAdapter');
        }
    }

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
