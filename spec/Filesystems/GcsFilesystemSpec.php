<?php

namespace spec\BackupManager\Filesystems;

use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class GcsFilesystemSpec extends ObjectBehavior
{
    public function let(): void
    {
        if (!class_exists('Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter')) {
            throw new SkippingException('Requires Flysystem GoogleStorageAdapter');
        }
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('BackupManager\Filesystems\GcsFilesystem');
    }

    public function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['gcs', 'GCS', 'Gcs'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    public function getConfig()
    {
        return [
            'type'         => 'gcs',
            'keyFilePath'  => '',
            'project'      => 'example',
            'bucket'       => 'example',
            'prefix'       => '',
        ];
    }
}
