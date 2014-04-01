<?php namespace McCool\DatabaseBackup\Procedures;

use McCool\DatabaseBackup\Dumpers\Dumper;
use McCool\DatabaseBackup\Storer;
use McCool\DatabaseBackup\Archivers\Archiver;

/**
 * Class BackupProcedure
 * @package McCool\DatabaseBackup\Procedures
 */
class BackupProcedure
{
    /**
     * The Backup Dumper instance.
     *
     * @var \McCool\DatabaseBackup\Dumpers\Dumper
     */
    protected $dumper;
    /**
     * @var array
     */
    protected $archivers = [];
    /**
     * The Backup Storer instance.
     *
     * @var \McCool\DatabaseBackup\Storer
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
     * @param  \McCool\DatabaseBackup\Dumpers\Dumper  $dumper
     * @return self
     */
    public function __construct(Dumper $dumper)
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
     * @param  \McCool\DatabaseBackup\Archivers\Archiver  $archiver
     * @return void
     */
    public function setArchiver(Archiver $archiver)
    {
        $this->archiver = $archiver;
    }

    /**
     * Inject a Storer
     *
     * @param  \McCool\DatabaseBackup\Storer  $storer
     * @return void
     */
    public function setStorer(Storer $storer)
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
