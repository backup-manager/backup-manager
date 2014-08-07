<?php namespace BigName\BackupManager\Databases;

use BigName\BackupManager\BackupManagerException;

/**
 * Class DatabaseTypeNotSupported
 * @package BigName\BackupManager\Databases
 */
class DatabaseTypeNotSupported extends \Exception implements BackupManagerException {}
