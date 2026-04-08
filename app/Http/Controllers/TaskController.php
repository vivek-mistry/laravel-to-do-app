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
    )
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request) : View
    {
        $tasks = $this->taskService->getAllTasks(
            filters: [
                'status' => $request->query('status'),
            ]
        );

        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * Load View
     *
     * @return View
     */
    public function create() : View
    {
        return view('tasks.create');
    }


    /**
     * Task Store
     *
     * @param TaskStoreRequest $request
     * @return RedirectResponse
     */
    public function store(TaskStoreRequest $request) : RedirectResponse
    {
        Task::create([
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function markAsDone(Task $task) : RedirectResponse
    {
        $task->update(['done' => true]);

        return redirect()->route('tasks.index')->with('success', 'Task marked as done!');
    }
}
