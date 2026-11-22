<?php

declare(strict_types=1);

namespace BackupManager\Procedures;

use BackupManager\Tasks\Task;

class Sequence
{
    /** @var list<Task> */
    private array $tasks = [];

    public function add(Task $task): void
    {
        $this->tasks[] = $task;
    }

    public function execute(): void
    {
        foreach ($this->tasks as $task) {
            $task->execute();
        }
    }
}
