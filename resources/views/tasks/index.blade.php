<x-tadieu-layout>

    @section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    
    @endsection

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
                            data-pk="{{ $task->id }}" data-type="text" data-title="Edit due date (YYYY-MM-DD)"
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

    @section('scripts')
    <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="https://momentjs.com/downloads/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/locale/en-in.min.js"></script>
    <script>
        const initEditable = () => {
        var taskStatus = $('#task-list').data('status') || '';

        $.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.ajaxOptions = { type: 'POST' };
        $.fn.editable.defaults.params = function(params) {
            params.status = taskStatus;
            return params;
        };

        
        $.fn.editableform.buttons = '<button type="submit" class="btn btn-primary btn-sm">Save</button>' +
            '<button type="button" class="btn btn-secondary btn-sm editable-cancel">Cancel</button>';
        $('.editable-field').editable({
            ajaxOptions: {
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            success: function(response, newValue) {
                if (this.dataset.name === 'due_date') {
                    window.location.reload();
                }
            },
            error: function(response, newValue) {
                if(response.status === 422) {
                    console.log(response);
                    alert(response.responseJSON.message);
                } else {
                    return response.responseText;
                }
            }
        });
    };

    $(function() {
        initEditable();
    });
    </script>
    @endsection
</x-tadieu-layout>