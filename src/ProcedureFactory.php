<?php namespace McCool\DatabaseBackup;

use McCool\DatabaseBackup\Factories\ArchiverFactory;
use McCool\DatabaseBackup\Factories\CommandFactory;
use McCool\DatabaseBackup\Factories\ConnectionFactory;
use McCool\DatabaseBackup\Factories\FilesystemFactory;
use McCool\DatabaseBackup\Procedures\BackupProcedure;

/**
 * Class ProcedureFactory
 * @package McCool\DatabaseBackup
 */
class ProcedureFactory
{
    /**
     * @var
     */
    private $commandFactory;

    public function __construct(Config $config)
    {
        $this->commandFactory = new CommandFactory(new FilesystemFactory($config), new ArchiverFactory(), $config);
    }

    /**
     * @return BackupProcedure
     */
    public function makeBackupProcedure()
    {
        return new BackupProcedure($this->commandFactory);
    }
} 
