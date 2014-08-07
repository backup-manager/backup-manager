<?php namespace BigName\BackupManager\Tasks\Compression;

use BigName\BackupManager\Tasks\Task;
use BigName\BackupManager\Compressors\Compressor;
use BigName\BackupManager\Shell\ShellProcessor;
use BigName\BackupManager\Shell\ShellProcessFailed;

/**
 * Class DecompressFile
 * @package BigName\BackupManager\Tasks\Compression
 */
class DecompressFile implements Task
{
    /**
     * @var string
     */
    private $sourcePath;
    /**
     * @var ShellProcessor
     */
    private $shellProcessor;
    /**
     * @var Compressor
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
     * @throws ShellProcessFailed
     */
    public function execute()
    {
        return $this->shellProcessor->process($this->compressor->getDecompressCommandLine($this->sourcePath));
    }
}
