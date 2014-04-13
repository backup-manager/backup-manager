<?php namespace BigName\BackupManager\Commands\Compression;

use BigName\BackupManager\Commands\Command;
use BigName\BackupManager\Compressors\Compressor;
use BigName\BackupManager\ShellProcessing\ShellProcessor;

/**
 * Class DecompressFile
 * @package BigName\BackupManager\Commands\Compression
 */
class DecompressFile implements Command
{
    /**
     * @var string
     */
    private $sourcePath;
    /**
     * @var \BigName\BackupManager\ShellProcessing\ShellProcessor
     */
    private $shellProcessor;
    /**
     * @var \BigName\BackupManager\Compressors\Compressor
     */
    private $compressor;

    /**
     * @param Compressor $compressor
     * @param $sourcePath
     * @param ShellProcessor $shellProcessor
     */
    public function __construct(Compressor $compressor, $sourcePath, ShellProcessor $shellProcessor)
    {
        $this->sourcePath = $sourcePath;
        $this->shellProcessor = $shellProcessor;
        $this->compressor = $compressor;
    }

    /**
     * @throws \BigName\BackupManager\ShellProcessing\ShellProcessFailed
     */
    public function execute()
    {
        return $this->shellProcessor->process($this->compressor->getDecompressCommandLine($this->sourcePath));
    }
}
