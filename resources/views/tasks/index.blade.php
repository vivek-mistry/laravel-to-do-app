<x-tadieu-layout>
    @section('content')
    <div class="card w-full">
        <a href="{{ route('tasks.create') }}" class="btn">Add New</a>

        <hr>

        <section>
            <form class="form flex gap-2 mb-6">
                <label for="filter_status">Filter tasks</label>
                <select id="filter_status" name="status">
                    <option value="">All</option>
                    <option value="open">Open</option>
                    <option value="overdue">Overdue</option>
                </select>
                <button type="submit" class="btn">Filter</button>
            </form>

            <ul class="grid gap-4">
                @forelse ($tasks as $task)
                    <li class="flex items-center gap-4">
                        <div class="flex flex-col gap-1 mr-auto">
                            <p class="text-sm font-medium leading-none {{ $task->done ? 'line-through' : '' }}">{{ $task->description }}</p>
                            @if ($task->due_date)
                                <p class="text-sm font-muted leading-none {{ $task->done ? 'line-through' : '' }}">{{ $task->due_date->format('d-m-Y') }}</p>
                            @endif
                        </div>
                        
                        @if (!$task->done)
                            <form action="{{ route('tasks.markDone', $task) }}" method="POST" class="form">
                                @csrf
                                @method('PATCH')
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
</x-tadieu-layout>