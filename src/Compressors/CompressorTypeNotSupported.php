<?php namespace BigName\BackupManager\Compressors;

use BigName\BackupManager\BackupManagerException;

/**
 * Class CompressorTypeNotSupported
 * @package BigName\BackupManager\Compressors
 */
class CompressorTypeNotSupported extends \Exception implements BackupManagerException {}
