<?php namespace McCool\DatabaseBackup;

class BackupProcedure
{
    private $config;

    private $dumper;
    private $archiver;
    private $storer;

    private $workingFile;

    public function __construct($dumper, $archiver, $storer)
    {
        $this->dumper   = $dumper;
        $this->archiver = $archiver;
        $this->storer   = $storer;
    }

    public function backup()
    {
        $this->dump();
        $this->archive();
        $this->store();
    }

    public function cleanup()
    {
        if (file_exists($this->workingFile)) {
            unlink($this->workingFile);
        }
    }

    private function dump()
    {
        $this->dumper->dump();

        $this->workingFile = $this->dumper->getOutputFilename();
    }

    private function archive()
    {
        if ($this->archiver) {
            $this->archiver->setInputFilename($this->workingFile);
            $this->archiver->archive();

            $this->workingFile = $this->archiver->getOutputFilename();
        }
    }

    private function store()
    {
        if ($this->storer) {
            $this->storer->setInputFilename($this->workingFile);

            $this->storer->store();
        }
    }
}