<?php namespace BackupManager\Compressors;

/**
 * Interface Compressor
 * @package BackupManager\Compressors
 */
interface Compressor
{
    /**
     * @param $type
     * @return bool
     */
    function handles($type);

    /**
     * @param $inputPath
     * @return string
     */
    function getCompressCommandLine($inputPath);

    /**
     * @param $outputPath
     * @return string
     */
    function getDecompressCommandLine($outputPath);

    /**
     * @param $inputPath
     * @return string
     */
    function getCompressedPath($inputPath);

    /**
     * @param $inputPath
     * @return string
     */
    function getDecompressedPath($inputPath);
}