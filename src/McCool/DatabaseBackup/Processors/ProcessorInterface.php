<?php namespace McCool\DatabaseBackup\Processors;

interface ProcessorInterface
{
    public function process($command);
    public function getErrors();
}