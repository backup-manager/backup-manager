<?php namespace McCool\DatabaseBackup;

use McCool\DatabaseBackup\Dumpers\DumperInterface;
use McCool\DatabaseBackup\Storers\StorerInterface;
use McCool\DatabaseBackup\Archivers\ArchiverInterface;

class BackupProcedure
{
    /**
     * The Backup Dumper instance.
     *
     * @var \McCool\DatabaseBackup\Dumpers\DumperInterface
     */
    private $dumper;

    /**
     * The Backup Archiver instance.
     *
     * @var \McCool\DatabaseBackup\Archivers\ArchiverInterface
     */
    private $archiver;

    /**
     * The Backup Storer instance.
     *
     * @var \McCool\DatabaseBackup\Storers\StorerInterface
     */
    private $storer;

    /**
     * The filename for the working file.
     *
     * @var string
     */
    private $workingFile;

    /**
     * Initializes the BackupProcedure instance.
     *
     * @param  \McCool\DatabaseBackup\Dumpers\DumperInterface  $dumper
     * @param  \McCool\DatabaseBackup\Archivers\ArchiverInterface  $archiver
     * @param  \McCool\DatabaseBackup\Storers\StorerInterface  $storer
     * @return self
     */
    public function __construct(DumperInterface $dumper, ArchiverInterface $archiver = null, StorerInterface $storer = null)
    {
        $this->dumper   = $dumper;
        $this->archiver = $archiver;
        $this->storer   = $storer;
    }

    /**
     * Executes the backup.
     *
     * @return void
     */
    public function backup()
    {
        $this->dump();
        $this->archive();
        $this->store();
    }

    /**
     * Cleans up the working file.
     *
     * @return void
     */
    public function cleanup()
    {
        if (file_exists($this->workingFile)) {
            unlink($this->workingFile);
        }
    }

    /**
     * Dumps the backup in the working file.
     *
     * @return void
     */
    private function dump()
    {
        $this->dumper->dump();

        $this->workingFile = $this->dumper->getOutputFilename();
    }

    /**
     * Gzips the working file.
     *
     * @return void
     */
    private function archive()
    {
        if ($this->archiver) {
            $this->archiver->setInputFilename($this->workingFile);
            $this->archiver->archive();

            $this->workingFile = $this->archiver->getOutputFilename();
        }
    }

    /**
     * Stores the working file into the storage provider.
     *
     * return void
     */
    private function store()
    {
        if ($this->storer) {
            $this->storer->setInputFilename($this->workingFile);

            $this->storer->store();
        }
    }
}