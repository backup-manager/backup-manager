<?php namespace Mccool\LaravelArtisanBackup\Storers;

Interface StorerInterface
{
    public function setInputFile($filename);
    public function store();
}