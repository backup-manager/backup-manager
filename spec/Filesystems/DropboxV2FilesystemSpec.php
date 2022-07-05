<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DropboxV2FilesystemSpec extends ObjectBehavior
{
    public function let(): void
    {
        if (!class_exists('Srmklive\Dropbox\Adapter\DropboxAdapter')) {
            throw new SkippingException('Requires Srmklive Dropbox');
        }
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('BackupManager\Filesystems\DropboxV2Filesystem');
    }

    public function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['dropboxv2', 'DropBoxV2', 'DROPBOXV2'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    public function getConfig()
    {
        return [
            'token' => 'token',
            'app'   => 'app',
            'root'  => 'some/directory/path',
        ];
    }
}
