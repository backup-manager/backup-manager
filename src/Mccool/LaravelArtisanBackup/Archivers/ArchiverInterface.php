<?php namespace Mccool\LaravelArtisanBackup\Archivers;

interface ArchiverInterface
{
    public function setInputFilename($filename);
    public function getOutputFilename();
    public function archive();
}