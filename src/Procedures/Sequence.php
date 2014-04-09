<?php namespace BigName\BackupManager\Procedures;

use BigName\BackupManager\Commands\Command;

class Sequence
{
    /**
     * @var array
     */
    private $commands = [];

    /**
     * @param Command $command
     */
    public function add(Command $command)
    {
        $this->commands[] = $command;
    }

    /**
     * Run the procedure.
     * @return void
     */
    public function execute()
    {
        foreach ($this->commands as $command) {
            $command->execute();
        }
    }
} 
