<?php namespace Mccool\LaravelArtisanBackup\Archivers;

use Mccool\LaravelArtisanBackup\Processors\ProcessorInterface;
use Mccool\LaravelArtisanBackup\Processors\ProcessorException;

class GzipArchiver implements ArchiverInterface
{
    private $processor;
    private $filename;

    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    public function setInputFilename($filename)
    {
        $this->filename = $filename;
    }

    public function getOutputFilename()
    {
        return $this->filename . '.gz';
    }

    public function archive()
    {
        $this->processor->process($this->getCommand());

        if ($this->processor->getErrors()) {
            throw new ProcessorException($this->processor->getErrors());
        }
    }

    private function getCommand()
    {
        return "gzip {$this->filename}";
    }
}