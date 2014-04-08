<?php namespace BigName\DatabaseBackup\Compressors;

abstract class Compressor
{
    abstract public function getCompressCommandLine($inputPath);
    abstract public function getDecompressCommandLine($outputPath);
    abstract public function getCompressedPath($inputPath);
}
