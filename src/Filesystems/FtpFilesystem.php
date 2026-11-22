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

        // FtpConnectionOptions reads 'root' without a default; v1 defaulted it to ''.
        $config += ['root' => ''];

        return new Flysystem(new FtpAdapter(FtpConnectionOptions::fromArray($config)));
    }
}
