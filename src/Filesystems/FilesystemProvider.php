<?php namespace BackupManager\Filesystems;

use BackupManager\Config\Config;
use BackupManager\Config\ConfigFieldNotFound;
use BackupManager\Config\ConfigNotFoundForConnection;

/**
 * Class FilesystemProvider
 * @package BackupManager\Filesystems
 */
class FilesystemProvider
{
    /** @var Config */
    private $config;
    /** @var array */
    private $filesystems = [];

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param Filesystem $filesystem
     */
    public function add(Filesystem $filesystem)
    {
        $this->filesystems[] = $filesystem;
    }

    /**
     * @param $name
     * @return \League\Flysystem\FilesystemInterface|\League\Flysystem\FilesystemOperator|object a Flysystem instance,
     *         or (FlysystemFilesystem with a Flysystem 2/3 mount manager) an object with readStream, writeStream and delete
     * @throws FilesystemTypeNotSupported
     * @throws ConfigNotFoundForConnection
     * @throws ConfigFieldNotFound
     */
    public function get($name)
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
     * @param $name
     * @param null $key
     * @return mixed
     * @throws ConfigNotFoundForConnection
     * @throws ConfigFieldNotFound
     */
    public function getConfig($name, $key = null)
    {
        return $this->config->get($name, $key);
    }

    /**
     * @param string|null $type
     * @return string
     */
    private function registrationHint($type)
    {
        $known = [
            'local' => LocalFilesystem::class,
            'awss3' => Awss3Filesystem::class,
            'asyncawss3' => AsyncAwsS3Filesystem::class,
            'async-awss3' => AsyncAwsS3Filesystem::class,
            'async-aws-s3' => AsyncAwsS3Filesystem::class,
            'b2' => BackblazeFilesystem::class,
            'rackspace' => RackspaceFilesystem::class,
            'dropboxv2' => DropboxV2Filesystem::class,
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
            return sprintf('Register it with $filesystemProvider->add(new \\%s);', $known[$key]);
        }

        return sprintf('Register a \\%s implementation that handles it.', Filesystem::class);
    }

    /**
     * @return array
     */
    public function getAvailableProviders()
    {
        return array_keys($this->config->getItems());
    }
}
