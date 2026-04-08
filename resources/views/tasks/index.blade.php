<x-tadieu-layout>

    @section('styles')
    
    @if (session('highlighted_task'))
    <style>
    .highlighted-task {
        animation: highlight-fade 2s ease forwards;
    }

    @keyframes highlight-fade {
        from {
            background-color: rgba(250, 204, 21, 0.35);
            border-color: rgba(252, 211, 77, 1);
        }

        to {
            background-color: transparent;
            border-color: transparent;
        }
    }
    </style>
    @endif
    @endsection

    @section('content')
    <div class="card w-full">
        <!-- <a href="{{ route('tasks.create') }}" class="btn">Add New</a> -->
        <section>
            <form action="{{ route('tasks.store') }}" class="form grid gap-6" method="Post">
                @method('Post')
                @csrf
                <div class="grid gap-2">
                    <label for="task_description">Description</label>
                    <div class="grid grid-cols-[1fr_180px] gap-2">
                        <input type="text" id="task_description" name="description" placeholder="describe the task..." tabindex="1" autofocus value="{{ old('description') }}">
                        <button type="submit" class="btn" tabindex="3">Add</button>
                        @error('description')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid gap-2">
                    <label for="task_due_date">Due date</label>
                    <input type="date" id="task_due_date" name="due_date" tabindex="2" value="{{ old('due_date') }}">
                    @error('due_date')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        </section>

        <hr>

        <section>
            <form action="{{ route('tasks.index') }}" method="GET" class="form flex gap-2 mb-6">
                <label for="filter_status">Filter tasks</label>
                <select id="filter_status" name="status">
                    <option value="" {{ $status === null || $status === '' ? 'selected' : '' }}>All</option>
                    <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
                <button type="submit" class="btn">Filter</button>
            </form>

            <ul id="task-list" class="grid gap-4" data-status="{{ $status }}">
                @forelse ($tasks as $task)
                <li class="flex items-center gap-4 {{ session('highlighted_task') == $task->id ? 'highlighted-task bg-yellow-100 dark:bg-yellow-900/20 border border-yellow-300' : '' }}"
                    data-task-id="{{ $task->id }}">
                    <div class="flex flex-col gap-1 mr-auto">
                        <a href="#"
                            class="editable-field text-sm font-medium leading-none {{ $task->done ? 'line-through' : '' }}"
                            data-name="description" data-url="{{ route('tasks.inlineUpdate', $task) }}"
                            data-pk="{{ $task->id }}" data-type="text" data-title="Edit description"
                            data-value="{{ $task->description }}">
                            {{ $task->description }}
                        </a>
                        <a href="#"
                            class="editable-field text-sm font-muted leading-none {{ $task->done ? 'line-through' : '' }}"
                            data-name="due_date" data-url="{{ route('tasks.inlineUpdate', $task) }}"
                            data-pk="{{ $task->id }}" data-type="date" data-title="Edit due date (YYYY-MM-DD)"
                            data-value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                            data-placeholder="YYYY-MM-DD">
                            {{ $task->due_date ? $task->due_date->format('d-m-Y') : 'No due date' }}
                        </a>
                    </div>

                    @if (!$task->done)
                    <form action="{{ route('tasks.markDone', $task) }}" method="POST" class="form">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $status }}">
                        <button type="submit" class="btn-sm-outline">Done</button>
                    </form>
                    @endif
                </li>
                @empty
                <li class="flex items-center gap-4">
                    <p class="text-sm font-medium leading-none">No tasks found.</p>
                </li>
                @endforelse

            </ul>
        </section>
    </div>

    

    @endsection

    @section('scripts')
    <script>
    </script>
    @endsection
</x-tadieu-layout>