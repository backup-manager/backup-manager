<?php namespace McCool\DatabaseBackup\Commands;

abstract class Command
{
    abstract public function getShellCommand();
} 
