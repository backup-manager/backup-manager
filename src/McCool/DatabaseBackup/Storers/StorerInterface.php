<?php namespace McCool\DatabaseBackup\Storers;

Interface StorerInterface
{
    public function setInputFilename($filename);
    public function store();
}