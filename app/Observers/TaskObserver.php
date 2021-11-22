<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    public function created(Task $task)
    {
        $task->recordActivity('created_task');
    }

}
