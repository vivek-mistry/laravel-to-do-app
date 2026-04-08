<x-tadieu-layout>
    @section('content')
    <div class="card w-full">
        <a href="{{ route('tasks.create') }}" class="btn">Add New</a>

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

            <ul class="grid gap-4">
                @forelse ($tasks as $task)
                <li class="flex items-center gap-4 {{ session('highlighted_task') == $task->id ? 'bg-yellow-100 dark:bg-yellow-900/20 border border-yellow-300 transition-colors duration-1000' : '' }}"
                    {{ session('highlighted_task') == $task->id ? 'data-highlight-task' : '' }}>
                    <div class="flex flex-col gap-1 mr-auto">
                        <p class="text-sm font-medium leading-none {{ $task->done ? 'line-through' : '' }}">
                            {{ $task->description }}
                        </p>
                        @if ($task->due_date)
                        <p class="text-sm font-muted leading-none {{ $task->done ? 'line-through' : '' }}">
                            {{ $task->due_date->format('d-m-Y') }}
                        </p>
                        @endif
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

    @if (session('highlighted_task'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var highlightRow = document.querySelector('[data-highlight-task]');

            if (!highlightRow) {
                return;
            }

            setTimeout(function() {
                highlightRow.classList.remove('bg-yellow-100');
                highlightRow.classList.remove('dark:bg-yellow-900/20');
                highlightRow.classList.remove('border');
                highlightRow.classList.remove('border-yellow-300');
            }, 1200);
        });
    </script>
    @endif

    @endsection
</x-tadieu-layout>