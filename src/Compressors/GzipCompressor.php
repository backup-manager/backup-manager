<?php namespace BigName\BackupManager\Compressors;

/**
 * Class GzipCompressor
 * @package BigName\BackupManager\Compressors
 */
class GzipCompressor extends Compressor
{
    /**
     * @param $inputPath
     * @return string
     */
    public function getCompressCommandLine($inputPath)
    {
        return 'gzip ' . escapeshellarg($inputPath);
    }

    /**
     * @param $outputPath
     * @return string
     */
    public function getDecompressCommandLine($outputPath)
    {
        return 'gunzip ' . escapeshellarg($outputPath);
    }

    /**
     * @param $inputPath
     * @return string
     */
    public function getCompressedPath($inputPath)
    {
        return $inputPath . '.gz';
    }

    /**
     * @param $inputPath
     * @return string
     */
    public function getDecompressedPath($inputPath)
    {
        return preg_replace('/.gz$/', '', $inputPath);
    }
}
