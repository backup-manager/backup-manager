<?php namespace BigName\BackupManager\Compressors;

abstract class Compressor
{
    abstract public function getCompressCommandLine($inputPath);
    abstract public function getDecompressCommandLine($outputPath);
    abstract public function getCompressedPath($inputPath);
    abstract public function getDecompressedPath($inputPath);
}
