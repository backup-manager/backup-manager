<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DropboxFilesystemSpec extends ObjectBehavior
{
    public function let(): void
    {
        if (!class_exists('Spatie\FlysystemDropbox\DropboxAdapter')) {
            throw new SkippingException('Requires Spatie Dropbox');
        }
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('BackupManager\Filesystems\DropboxFilesystem');
    }

    public function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['dropbox', 'DropBox', 'DROPBOX'] as $type) {
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
