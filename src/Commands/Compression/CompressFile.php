<?php namespace BigName\BackupManager\Commands\Compression;

use BigName\BackupManager\Commands\Command;
use BigName\BackupManager\Compressors\Compressor;
use BigName\BackupManager\ShellProcessing\ShellProcessor;

class CompressFile implements Command
{
    /**
     * @var
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

    public function __construct(Compressor $compressor, $sourcePath, ShellProcessor $shellProcessor)
    {
        $this->compressor = $compressor;
        $this->sourcePath = $sourcePath;
        $this->shellProcessor = $shellProcessor;
    }

    public function execute()
    {
        return $this->shellProcessor->process($this->compressor->getCompressCommandLine($this->sourcePath));
    }
}
