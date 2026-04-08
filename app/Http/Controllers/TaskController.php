<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(
        \App\Services\TaskServices $taskService
    ) {
        $this->taskService = $taskService;
    }

    public function index(Request $request): View
    {
        $status = $request->query('status');

        $tasks = $this->taskService->getAllTasks(
            filters: [
                'status' => $status,
            ]
        );

        return view('tasks.index', [
            'tasks' => $tasks,
            'status' => $status,
        ]);
    }

    /**
     * Load View
     *
     * @return View
     */
    public function create(): View
    {
        return view('tasks.create');
    }


    /**
     * Task Store
     *
     * @param TaskStoreRequest $request
     * @return RedirectResponse
     */
    public function store(TaskStoreRequest $request): RedirectResponse
    {
        $task = Task::create([
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!')
            ->with('highlighted_task', $task->id);
    }

    public function markAsDone(Request $request, Task $task): RedirectResponse
    {
        $task->update(['done' => true]);

        return redirect()->route('tasks.index', [
            'status' => $request->input('status'),
        ])
            ->with('success', 'Task marked as done!');
    }
}
