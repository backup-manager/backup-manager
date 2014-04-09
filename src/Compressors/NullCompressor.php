<?php namespace BigName\DatabaseBackup\Compressors; 

class NullCompressor extends Compressor
{
    public function getCompressCommandLine($inputPath)
    {
        return '';
    }

    public function getDecompressCommandLine($outputPath)
    {
        return '';
    }

    public function getCompressedPath($inputPath)
    {
        return $inputPath;
    }

    public function getDecompressedPath($inputPath)
    {
        return $inputPath;
    }
}
