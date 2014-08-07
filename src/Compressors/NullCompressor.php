<?php namespace BigName\BackupManager\Compressors;

/**
 * Class NullCompressor
 * @package BigName\BackupManager
 */
class NullCompressor implements  Compressor
{
    /**
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type) == 'null';
    }

    /**
     * @param $inputPath
     * @return string
     */
    public function getCompressCommandLine($inputPath)
    {
        return '';
    }

    /**
     * @param $outputPath
     * @return string
     */
    public function getDecompressCommandLine($outputPath)
    {
        return '';
    }

    /**
     * @param $inputPath
     * @return string
     */
    public function getCompressedPath($inputPath)
    {
        return $inputPath;
    }

    /**
     * @param $inputPath
     * @return string
     */
    public function getDecompressedPath($inputPath)
    {
        return $inputPath;
    }
}
