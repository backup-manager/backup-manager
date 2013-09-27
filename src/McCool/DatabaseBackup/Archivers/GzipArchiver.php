<?php namespace McCool\DatabaseBackup\Archivers;

use McCool\DatabaseBackup\Processors\ProcessorException;
use McCool\DatabaseBackup\Processors\ProcessorInterface;

class GzipArchiver implements ArchiverInterface
{
    /**
     * The processor instance.
     *
     * @var \McCool\DatabaseBackup\Processors\ProcessorInterface
     */
    protected $processor;

    /**
     * The backup filename.
     *
     * @var string
     */
    protected $filename;

    /**
     * Initializes the GzipArchiver instance.
     *
     * @param  \McCool\DatabaseBackup\Processors\ProcessorInterface  $processor
     * @return self
     */
    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Sets the filename for the backup.
     *
     * When you provide the filename make sure you obsolete the
     * file extension as this gets added automatically.
     *
     * @param  string  $filename
     * @return void
     */
    public function setInputFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Returns the filename for the backup.
     *
     * @return string
     */
    public function getOutputFilename()
    {
        return $this->filename . '.gz';
    }

    /**
     * Executes the backup command.
     *
     * @return void
     * @throws \McCool\DatabaseBackup\Processors\ProcessorException
     */
    public function archive()
    {
        $this->processor->process($this->getCommand());

        if ($this->processor->getErrors()) {
            throw new ProcessorException($this->processor->getErrors());
        }
    }

    /**
     * Returns the backup command.
     *
     * @return string
     */
    protected function getCommand()
    {
        return "gzip {$this->filename}";
    }
}