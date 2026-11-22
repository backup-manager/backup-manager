<?php

namespace spec\BackupManager\Support;

use BackupManager\Filesystems\FilesystemAdapterMissing;

/**
 * For filesystem factory specs: the factory builds the preferred installed
 * adapter, or throws FilesystemAdapterMissing when none is installed.
 */
trait ExpectsAdapters
{
    public function getMatchers(): array
    {
        return [
            'useAdapter' => function ($filesystem, $class) {
                return Adapters::of($filesystem) instanceof $class;
            },
        ];
    }

    /**
     * @param string[] $candidates adapter classes in the factory's order of preference
     * @param array $config
     */
    private function expectAdapter(array $candidates, array $config)
    {
        $adapter = Adapters::firstInstalled($candidates);

        if ($adapter === null) {
            $this->shouldThrow(FilesystemAdapterMissing::class)->during('get', [$config]);
            return;
        }

        $this->get($config)->shouldUseAdapter($adapter);
    }
}
