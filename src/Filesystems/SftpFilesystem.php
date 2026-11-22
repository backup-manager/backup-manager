<?php namespace BackupManager\Filesystems;

use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\PhpseclibV2\SftpAdapter as PhpseclibV2SftpAdapter;
use League\Flysystem\PhpseclibV2\SftpConnectionProvider as PhpseclibV2SftpConnectionProvider;
use League\Flysystem\PhpseclibV3\SftpAdapter as PhpseclibV3SftpAdapter;
use League\Flysystem\PhpseclibV3\SftpConnectionProvider as PhpseclibV3SftpConnectionProvider;
use League\Flysystem\Sftp\SftpAdapter;

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
        // Flysystem v3 with league/flysystem-sftp-v3 (phpseclib 3)
        if (class_exists(PhpseclibV3SftpAdapter::class)) {
            return new Flysystem(new PhpseclibV3SftpAdapter(
                PhpseclibV3SftpConnectionProvider::fromArray($this->connectionOptions($config)),
                $config['root'] ?? ''
            ));
        }

        // Flysystem v2/v3 with league/flysystem-sftp (phpseclib 2)
        if (class_exists(PhpseclibV2SftpAdapter::class)) {
            return new Flysystem(new PhpseclibV2SftpAdapter(
                PhpseclibV2SftpConnectionProvider::fromArray($this->connectionOptions($config)),
                $config['root'] ?? ''
            ));
        }

        // Flysystem v1 with league/flysystem-sftp
        if (class_exists(SftpAdapter::class)) {
            return new Flysystem(new SftpAdapter($config));
        }

        throw FilesystemAdapterMissing::forType('Sftp', 'league/flysystem-sftp-v3');
    }

    /**
     * Maps a Flysystem 1 style config onto the v2/v3 connection providers:
     * - empty credentials are absent (v2/v3 try to use any non-null value);
     * - with a private key and no 'passphrase', 'password' unlocks the key,
     *   as in Flysystem 1;
     * - scalars are cast, because the providers are strictly typed.
     *
     * @param array $config
     * @return array
     */
    private function connectionOptions(array $config)
    {
        foreach (['password', 'privateKey', 'passphrase'] as $key) {
            if (isset($config[$key]) && $config[$key] === '') {
                $config[$key] = null;
            }
        }

        if (isset($config['privateKey']) && ! isset($config['passphrase']) && isset($config['password'])) {
            $config['passphrase'] = $config['password'];
        }

        foreach (['port', 'timeout', 'maxTries'] as $key) {
            if (isset($config[$key])) {
                $config[$key] = (int) $config[$key];
            }
        }
        if (isset($config['useAgent'])) {
            $config['useAgent'] = filter_var($config['useAgent'], FILTER_VALIDATE_BOOLEAN);
        }

        return $config;
    }
}
