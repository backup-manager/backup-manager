<?php namespace BigName\BackupManager\Compressors;

/**
 * Class Compressor
 * @package BigName\BackupManager\Compressors
 */
abstract class Compressor
{
    /**
     * @param $type
     * @return bool
     */
    abstract public function handles($type);

    /**
     * @param $inputPath
     * @return string
     */
    abstract public function getCompressCommandLine($inputPath);

    /**
     * @param $outputPath
     * @return string
     */
    abstract public function getDecompressCommandLine($outputPath);

    /**
     * @param $inputPath
     * @return string
     */
    abstract public function getCompressedPath($inputPath);

    /**
     * @param $inputPath
     * @return string
     */
    abstract public function getDecompressedPath($inputPath);
}
