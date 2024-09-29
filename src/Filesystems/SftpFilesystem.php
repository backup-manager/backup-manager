<?php namespace BackupManager\Filesystems;

use League\Flysystem\PhpseclibV3\SftpConnectionProvider;
use League\Flysystem\PhpseclibV3\SftpAdapter;
use League\Flysystem\Filesystem as Flysystem;

/**
 * Class SftpFilesystem
 * @package BackupManager\Filesystems
 */
class SftpFilesystem implements Filesystem
{
    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type ?? '') == 'sftp';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        $keys = array_flip(['host', 'username', 'password', 'privateKey', 'passphrase', 'port', 'useAgent', 'timeout', 'maxTries', 'hostFingerprint', 'connectivityChecker', 'preferredAlgorithms']);
        $connection = array_intersect_key($config["connection"] ?? [], $keys);
        $connectionProvider = SftpConnectionProvider::fromArray($connection);

        return new Flysystem(new SftpAdapter($connectionProvider, $config["root"] ?? ""));
    }
}
