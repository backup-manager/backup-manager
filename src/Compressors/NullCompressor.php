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
     * @return null
     */
    public function getCompressCommandLine($inputPath)
    {
        return null;
    }

    /**
     * @param $outputPath
     * @return null
     */
    public function getDecompressCommandLine($outputPath)
    {
        return null;
    }

    /**
     * @param $inputPath
     * @return null
     */
    public function getCompressedPath($inputPath)
    {
        return null;
    }

    /**
     * @param $inputPath
     * @return null
     */
    public function getDecompressedPath($inputPath)
    {
        return null;
    }
}
