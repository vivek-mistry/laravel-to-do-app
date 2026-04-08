<?php

namespace App\Services;

use App\Models\Task;

class TaskServices
{
    public function getAllTasks($filters = [])
    {
        $query = Task::query();

        $query->when(isset($filters['status']) && $filters['status'] === 'open', function ($query) use ($filters) {
            $query->where('done', false);
        });

        $query->when(isset($filters['status']) && $filters['status'] === 'overdue', function ($query) use ($filters) {
            $query->where('due_date', '<', now())
                  ->where('done', false);
        });

        return $query->get();
    }
}
