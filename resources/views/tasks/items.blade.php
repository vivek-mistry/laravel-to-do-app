@forelse ($tasks as $task)
<li class="flex items-center gap-4 {{ session('highlighted_task') == $task->id ? 'highlighted-task bg-yellow-100 dark:bg-yellow-900/20 border border-yellow-300' : '' }}"
    data-task-id="{{ $task->id }}" data-task-description="{{ e($task->description) }}"
    data-task-due-date="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
    data-update-url="{{ route('tasks.inlineUpdate', $task) }}">
    <div class="task-view flex flex-col gap-1 mr-auto">
        <div class="text-sm font-medium leading-none {{ $task->done ? 'line-through' : '' }}">
            {{ $task->description }}
        </div>
        <div class="text-sm font-muted leading-none {{ $task->done ? 'line-through' : '' }}">
            {{ $task->due_date ? $task->due_date->format('d-m-Y') : 'No due date' }}
        </div>
    </div>

    @if (!$task->done)
    <div class="flex gap-2 items-center">
        <button type="button" class="btn-sm-outline task-edit-button">Edit</button>
        <form action="{{ route('tasks.markDone', $task) }}" method="POST" class="form">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="{{ $status }}">
            <button type="submit" class="btn-sm-outline">Done</button>
        </form>
    </div>
    @endif
</li>
@empty
<li class="flex items-center gap-4">
    <p class="text-sm font-medium leading-none">No tasks found.</p>
</li>
@endforelse