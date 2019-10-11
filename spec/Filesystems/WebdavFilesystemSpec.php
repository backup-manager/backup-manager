<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WebdavFilesystemSpec extends ObjectBehavior {

    function it_is_initializable() {
        $this->shouldHaveType('BackupManager\Filesystems\WebdavFilesystem');
    }

    function it_should_recognize_its_type_with_case_insensitivity() {
        foreach (['Webdav', 'WEBDAV', 'WeBdAv'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_provide_an_instance_of_an_webdav_filesystem() {
        $this->get($this->getConfig())->getAdapter()
            ->shouldHaveType('League\Flysystem\WebDAV\WebDAVAdapter');
    }

    function getConfig() {
        return [
            'baseUri'  => 'http://myserver.com',
            'userName' => '',
            'password' => '',
            'prefix'   => '',
        ];
    }
}
