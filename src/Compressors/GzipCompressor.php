<?php namespace BigName\BackupManager\Compressors;

class GzipCompressor extends Compressor
{
    public function getCompressCommandLine($inputPath)
    {
        return 'gzip ' . escapeshellarg($inputPath);
    }

    public function getDecompressCommandLine($outputPath)
    {
        return 'gunzip ' . escapeshellarg($outputPath);
    }

    public function getCompressedPath($inputPath)
    {
        return $inputPath . '.gz';
    }

    public function getDecompressedPath($inputPath)
    {
        return preg_replace('/.gz$/', '', $inputPath);
    }
}
