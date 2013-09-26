<?php namespace McCool\DatabaseBackup\Dumpers;

interface DumperInterface
{
    public function dump();
    public function getOutputFilename();
}