<?php

declare(strict_types=1);

namespace BackupManager\Filesystems;

use BackupManager\Config\Config;
use BackupManager\Config\ConfigFieldNotFound;
use BackupManager\Config\ConfigNotFoundForConnection;
use League\Flysystem\FilesystemOperator;

class FilesystemProvider
{
    /** @var list<Filesystem> */
    private array $filesystems = [];

    public function __construct(private readonly Config $config)
    {
    }

    public function add(Filesystem $filesystem): void
    {
        $this->filesystems[] = $filesystem;
    }

    /**
     * @throws FilesystemTypeNotSupported
     * @throws ConfigNotFoundForConnection
     * @throws ConfigFieldNotFound
     */
    public function get(string $name): FilesystemOperator
    {
        $type = $this->getConfig($name, 'type');

        foreach ($this->filesystems as $filesystem) {
            if ($filesystem->handles($type)) {
                return $filesystem->get($this->config->get($name));
            }
        }

        throw new FilesystemTypeNotSupported(
            "The requested filesystem type {$type} is not currently supported. " . $this->registrationHint($type)
        );
    }

    /**
     * @throws ConfigNotFoundForConnection
     * @throws ConfigFieldNotFound
     */
    public function getConfig(string $name, ?string $key = null): mixed
    {
        return $this->config->get($name, $key);
    }

    /**
     * @return list<string> connection names
     */
    public function getAvailableProviders(): array
    {
        return array_keys($this->config->getItems());
    }

    private function registrationHint(?string $type): string
    {
        $known = [
            'local' => LocalFilesystem::class,
            'awss3' => Awss3Filesystem::class,
            'asyncawss3' => AsyncAwsS3Filesystem::class,
            'async-awss3' => AsyncAwsS3Filesystem::class,
            'async-aws-s3' => AsyncAwsS3Filesystem::class,
            'gcs' => GcsFilesystem::class,
            'dropbox' => DropboxFilesystem::class,
            'ftp' => FtpFilesystem::class,
            'sftp' => SftpFilesystem::class,
            'webdav' => WebdavFilesystem::class,
        ];
        $key = strtolower((string) $type);

        if ($key === 'flysystem') {
            return sprintf('Register it with $filesystemProvider->add(new \\%s($filesystemsByName));', FlysystemFilesystem::class);
        }

        if (isset($known[$key])) {
            return sprintf('Register it with $filesystemProvider->add(new \\%s());', $known[$key]);
        }

        if (in_array($key, ['b2', 'rackspace', 'dropboxv2'], true)) {
            return 'This type was removed in 4.0; see UPGRADE.md.';
        }

        return sprintf('Register a \\%s implementation that handles it.', Filesystem::class);
    }
}
