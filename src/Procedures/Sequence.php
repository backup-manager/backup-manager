<?php namespace BackupManager\Procedures;

use BackupManager\Tasks\Task;

/**
 * Class Sequence
 * @package BackupManager\Procedures
 */
class Sequence
{
    /** @var array|Task[] */
    private $tasks = [];

    /**
     * @param Task $task
     */
    public function add(Task $task)
    {
        $this->tasks[] = $task;
    }

    /**
     * Run the procedure.
     * @return void
     */
    public function execute()
    {
        foreach ($this->tasks as $task) {
            $task->execute();
        }
    }
}
