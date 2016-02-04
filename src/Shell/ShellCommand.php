<?php namespace BackupManager\Shell;

class ShellCommand
{
    /** @var string */
    private $command;

    public function __construct($command) {
        if (empty($command))
            throw new InvalidShellCommand("Give command [{$command}] is not valid.");
        $this->command = $command;
    }

    public function command()
    {
        return $this->command;
    }
}