<?php namespace McCool\DatabaseBackup\Archivers;

interface ArchiverInterface
{
    public function setInputFilename($filename);
    public function getOutputFilename();
    public function archive();
}