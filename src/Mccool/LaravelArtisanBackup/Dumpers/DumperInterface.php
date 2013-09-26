<?php namespace Mccool\LaravelArtisanBackup\Dumpers;

interface DumperInterface
{
    public function dump();
    public function getOutputFilename();
}