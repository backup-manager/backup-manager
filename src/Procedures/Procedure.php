<?php namespace BigName\DatabaseBackup\Procedures;

use BigName\DatabaseBackup\Commands\Command;
use BigName\DatabaseBackup\Commands\CommandFactory;
use BigName\DatabaseBackup\Sequence;

/**
 * Class Procedure
 * @package Procedures
 */
abstract class Procedure
{
    /**
     * @var \BigName\DatabaseBackup\Commands\CommandFactory
     */
    protected $factory;

    private $sequence;

    public function __construct(CommandFactory $factory, Sequence $sequence)
    {
        $this->factory = $factory;
        $this->sequence = $sequence;
    }

    protected function add(Command $command)
    {
        $this->sequence->add($command);
    }

    protected function execute()
    {
        $this->sequence->execute();
    }
} 
