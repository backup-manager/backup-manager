<?php namespace BigName\DatabaseBackup\Procedures;
use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\Factories\CommandFactory;

/**
 * Class Procedure
 * @package Procedures
 */
abstract class Procedure
{
    /**
     * @var \BigName\DatabaseBackup\Factories\CommandFactory
     */
    protected $commandFactory;
    /**
     * @var array
     */
    private $commands = [];

    public function __construct(CommandFactory $commandFactory)
    {
        $this->commandFactory = $commandFactory;
    }

    /**
     * Run the procedure.
     * @return mixed
     */
    protected function execute()
    {
        foreach ($this->commands as $command) {
            $command->execute();
        }
    }

    /**
     * @param Command $command
     */
    protected function add(Command $command)
    {
        $this->commands[] = $command;
    }
} 
