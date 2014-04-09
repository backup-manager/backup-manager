<?php namespace BigName\BackupManager\Commands\Compression;

use BigName\BackupManager\Commands\Command;
use BigName\BackupManager\Compressors\Compressor;
use BigName\BackupManager\ShellProcessing\ShellProcessor;

class DecompressFile implements Command
{
    private $sourcePath;
    /**
     * @var \BigName\BackupManager\ShellProcessing\ShellProcessor
     */
    private $shellProcessor;
    /**
     * @var \BigName\BackupManager\Compressors\Compressor
     */
    private $compressor;

    public function __construct(Compressor $compressor, $sourcePath, ShellProcessor $shellProcessor)
    {
        $this->sourcePath = $sourcePath;
        $this->shellProcessor = $shellProcessor;
        $this->compressor = $compressor;
    }

    public function execute()
    {
        return $this->shellProcessor->process($this->compressor->getDecompressCommandLine($this->sourcePath));
    }
}
