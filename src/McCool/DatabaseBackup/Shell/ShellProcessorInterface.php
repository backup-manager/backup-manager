<?php namespace McCool\DatabaseBackup\Shell;

interface ShellProcessorInterface
{
    public function process($command);
    public function getErrors();
}
