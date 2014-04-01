<?php namespace McCool\DatabaseBackup;

interface CommandProcessor
{
    public function process($command);
    public function getErrors();
}
