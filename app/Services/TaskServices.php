<?php

namespace App\Services;

use App\Models\Task;

class TaskServices
{
    public function getAllTasks($filters = [])
    {
        $query = Task::query();

        $query->when(isset($filters['status']) && $filters['status'] === 'open', function ($query) {
            $query->where('done', false);
        });

        $query->when(isset($filters['status']) && $filters['status'] === 'overdue', function ($query) {
            $query->where('due_date', '<', now())
                ->where('done', false);
        });

        $query->orderBy('done')
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->orderByDesc('created_at');

        return $query->get();
    }
}
