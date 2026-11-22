<?php namespace BackupManager\Filesystems;

use League\Flysystem\Adapter\Ftp;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;

/**
 * Class FtpFilesystem
 * @package BackupManager\Filesystems
 */
class FtpFilesystem implements Filesystem
{
    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type ?? '') == 'ftp';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        // Flysystem v1 ships the FTP adapter in core.
        if (class_exists(Ftp::class)) {
            return new Flysystem(new Ftp($config));
        }

        if (! class_exists(FtpAdapter::class)) {
            throw FilesystemAdapterMissing::forType('Ftp', 'league/flysystem-ftp');
        }

        return new Flysystem(new FtpAdapter(FtpConnectionOptions::fromArray($this->connectionOptions($config))));
    }

    /**
     * FtpConnectionOptions is strictly typed; Flysystem 1 accepted strings
     * such as a port read from an environment variable.
     *
     * @param array $config
     * @return array
     */
    private function connectionOptions(array $config)
    {
        $config += ['root' => ''];

        foreach (['port', 'timeout'] as $key) {
            if (isset($config[$key])) {
                $config[$key] = (int) $config[$key];
            }
        }
        foreach (['ssl', 'passive', 'utf8', 'ignorePassiveAddress', 'timestampsOnUnixListingsEnabled', 'recurseManually'] as $key) {
            if (isset($config[$key])) {
                $config[$key] = filter_var($config[$key], FILTER_VALIDATE_BOOLEAN);
            }
        }

        return $config;
    }
}
