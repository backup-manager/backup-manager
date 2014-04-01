<?php namespace McCool\DatabaseBackup\Gzip;

use McCool\DatabaseBackup\Archivers\Archiver;
use McCool\DatabaseBackup\Shell\ShellProcessorException;
use McCool\DatabaseBackup\CommandProcessor;

/**
 * Class Gzip
 * @package McCool\DatabaseBackup\Gzip
 */
class Gzip implements Archiver
{
    /**
     * The processor instance.
     *
     * @var \McCool\DatabaseBackup\CommandProcessor
     */
    protected $processor;

    /**
     * Initializes the Gzip instance.
     *
     * @param  \McCool\DatabaseBackup\CommandProcessor  $processor
     * @return self
     */
    public function __construct(CommandProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @return array of file extensions
     */
    public function handlesFileExtensions()
    {
        return ['gz', 'zip'];
    }

    /**
     * @param $filePath
     * @return bool
     */
    public function canHandleFile($filePath)
    {
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        foreach ($this->handlesFileExtensions() as $extension) {
            if ($fileExtension == $extension) {
                return true;
            }
        }
        return false;
    }

    /**
     * Executes the backup command.
     * @param string $filePath
     * @return void
     * @throws \McCool\DatabaseBackup\Shell\ShellProcessorException
     */
    public function archive($filePath)
    {
        $this->processor->process($this->getArchiveCommand($filePath));
        $this->handleProcessorErrors();
        return $this->getArchivedFilename($filePath);
    }

    /**
     * @param $filePath to extract
     * @return string filename of the extracted file
     * @throws \McCool\DatabaseBackup\Shell\ShellProcessorException
     */
    public function extract($filePath)
    {
        $this->processor->process($this->getExtractCommand($filePath));
        $this->handleProcessorErrors();
    }

    /**
     * @param $filename
     * @return string
     */
    protected function getArchiveCommand($filename)
    {
        return "gzip {$filename}";
    }

    /**
     * @param $filename
     * @return string
     */
    protected function getExtractCommand($filename)
    {
        return "gunzip {$filename}";
    }

    /**
     * @throws \McCool\DatabaseBackup\Shell\ShellProcessorException
     */
    private function handleProcessorErrors()
    {
        if ($this->processor->getErrors()) {
            throw new ShellProcessorException($this->processor->getErrors());
        }
    }

    /**
     * @param string $filePath
     * @return string
     */
    private function getArchivedFilename($filePath)
    {
        return $filePath . '.gz';
    }
}
