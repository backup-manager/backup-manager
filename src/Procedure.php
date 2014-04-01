<?php namespace McCool\DatabaseBackup; 

class Procedure
{
    private $sequence = [];

    public function __construct(array $sequence)
    {
        $this->sequence = $sequence;
    }
} 
