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
    protected $dumper;

    /**
     * The Backup Archiver instance.
     *
     * @var \McCool\DatabaseBackup\Archivers\ArchiverInterface
     */
    protected $archiver;

    /**
     * The Backup Storer instance.
     *
     * @var \McCool\DatabaseBackup\Storers\StorerInterface
     */
    protected $storer;

    /**
     * The filename for the working file.
     *
     * @var string
     */
    protected $workingFile;

    /**
     * Initializes the BackupProcedure instance.
     *
     * @param  \McCool\DatabaseBackup\Dumpers\DumperInterface  $dumper
     * @return self
     */
    public function __construct(DumperInterface $dumper)
    {
        $this->dumper = $dumper;
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
     * Inject an Archiver
     *
     * @param  \McCool\DatabaseBackup\Archivers\ArchiverInterface  $archiver
     * @return void
     */
    public function setArchiver(ArchiverInterface $archiver)
    {
        $this->archiver = $archiver;
    }

    /**
     * Inject a Storer
     *
     * @param  \McCool\DatabaseBackup\Storers\StorerInterface  $storer
     * @return void
     */
    public function setStorer(StorerInterface $storer)
    {
        $this->storer = $storer;
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
    protected function dump()
    {
        $this->dumper->dump();

        $this->workingFile = $this->dumper->getOutputFilename();
    }

    /**
     * Gzips the working file.
     *
     * @return void
     */
    protected function archive()
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
    protected function store()
    {
        if ($this->storer) {
            $this->storer->setInputFilename($this->workingFile);

            $this->storer->store();
        }
    }
}