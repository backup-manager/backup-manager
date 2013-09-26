<?php namespace Mccool\LaravelArtisanBackup\Processors;

use Symfony\Component\Process\Process;

class ShellProcessor implements ProcessorInterface
{
    private $process;

    public function process($command)
    {
        $this->process = new Process($command);
        $this->process->run();
    }

    public function wasSuccessful()
    {
        if ( ! isset($this->process)) return null;

        if ($this->process->isSuccessful()) {
            return true;
        }

        return false;
    }

    public function getErrors()
    {
        if ( ! isset($this->process)) return null;

        return $this->process->getErrorOutput();
    }
}