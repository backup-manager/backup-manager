<?php namespace BackupManager\Tasks\Storage;

/**
 * Thrown when a Flysystem v1 read or write reports failure by returning false.
 * Flysystem v2/v3 throw their own exceptions, which propagate unchanged.
 *
 * @package BackupManager\Tasks\Storage
 */
class FileTransferFailed extends \RuntimeException
{
}
