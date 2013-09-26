<?php namespace Mccool\LaravelArtisanBackup\Storers;

Interface StorerInterface
{
    public function setInputFilename($filename);
    public function store();
}