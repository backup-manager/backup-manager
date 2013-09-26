<?php namespace McCool\DatabaseBackup;

class BackupProcedure
{
    private $config;

    private $dumper;
    private $archiver;
    private $storer;

    public function __construct($dumper, $archiver, $storer)
    {
        $this->dumper   = $dumper;
        $this->archiver = $archiver;
        $this->storer   = $storer;
    }

    public function backup()
    {
        $this->dumper->dump();

        $this->archiver->setInputFilename($this->dumper->getOutputFilename());
        $this->archiver->archive();

        $this->storer->setInputFilename($this->archiver->getOutputFilename());
        $this->storer->store();
    }
}