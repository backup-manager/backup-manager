<?php namespace BigName\BackupManager\Filesystems;

use BigName\BackupManager\BackupManagerException;

/**
 * Class FilesystemTypeNotSupported
 * @package BigName\BackupManager\Filesystems
 */
class FilesystemTypeNotSupported extends \Exception implements BackupManagerException {}
