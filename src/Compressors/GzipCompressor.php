<?php namespace BackupManager\Compressors;

/**
 * Class GzipCompressor
 * @package BackupManager\Compressors
 */
class GzipCompressor extends Compressor {

    /**
     * @param $type
     * @return bool
     */
    public function handles($type) {
        return strtolower($type) == 'gzip';
    }

    /**
     * @param $inputPath
     * @return string
     */
    public function getCompressCommandLine($inputPath) {
        return 'gzip ' . escapeshellarg($inputPath);
    }

    /**
     * @param $outputPath
     * @return string
     */
    public function getDecompressCommandLine($outputPath) {
        return 'gunzip ' . escapeshellarg($outputPath);
    }

    /**
     * @param $inputPath
     * @return string
     */
    public function getCompressedPath($inputPath) {
        return $inputPath . '.gz';
    }

    /**
     * @param $inputPath
     * @return string
     */
    public function getDecompressedPath($inputPath) {
        return preg_replace('/.gz$/', '', $inputPath);
    }
}
