<?php

declare(strict_types=1);

namespace BackupManager\Tasks;

interface Task
{
    public function execute(): void;
}
