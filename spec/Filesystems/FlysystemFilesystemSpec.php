<?php

namespace spec\BackupManager\Filesystems;

use BackupManager\Filesystems\FlysystemFilesystem;
use BackupManager\Filesystems\LocalFilesystem;
use League\Flysystem\MountManager;
use PhpSpec\ObjectBehavior;
use spec\BackupManager\Support\StorageDouble;

class FlysystemFilesystemSpec extends ObjectBehavior
{
    /** @var string */
    private $root;

    public function let()
    {
        $this->root = sys_get_temp_dir() . '/backup-manager-spec-' . uniqid();
        mkdir($this->root);
    }

    public function letGo()
    {
        array_map('unlink', glob($this->root . '/*') ?: []);
        rmdir($this->root);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FlysystemFilesystem::class);
    }

    public function it_should_recognize_its_type_with_case_insensitivity()
    {
        foreach (['flysystem', 'Flysystem', 'FlySystem'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    public function it_should_provide_a_filesystem_by_name(StorageDouble $filesystem)
    {
        $name = 's3_backup';
        $this->beConstructedWith([$name => $filesystem]);
        $this->get(['name' => $name])->shouldBe($filesystem);
    }

    public function it_should_provide_a_mount_manager_filesystem_by_prefix()
    {
        $local = (new LocalFilesystem())->get(['root' => $this->root]);
        $this->beConstructedWith([], new MountManager(['upload' => $local]));

        $filesystem = $this->get(['prefix' => 'upload']);

        $stream = fopen('php://memory', 'r+');
        fwrite($stream, 'dump');
        rewind($stream);
        $filesystem->writeStream('backup.sql', $stream);

        if (file_get_contents($this->root . '/backup.sql') !== 'dump') {
            throw new \RuntimeException('Expected the mounted filesystem to receive the write.');
        }

        $filesystem->delete('backup.sql');

        if (file_exists($this->root . '/backup.sql')) {
            throw new \RuntimeException('Expected the mounted filesystem to receive the delete.');
        }
    }
}
