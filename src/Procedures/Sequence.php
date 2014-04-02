<?php namespace BigName\DatabaseBackup\Procedures; 

use BigName\DatabaseBackup\Commands\Command;

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
