<?php namespace BigName\BackupManager\Procedures;

use BigName\BackupManager\Tasks\Task;

/**
 * Class Sequence
 * @package BigName\BackupManager\Procedures
 */
class Sequence
{
    /**
     * @var array|Task[]
     */
    private $tasks = [];

    /**
     * @param \BigName\BackupManager\Tasks\Task $task
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
