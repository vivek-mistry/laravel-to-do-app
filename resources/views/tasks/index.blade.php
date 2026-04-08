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

        <section>
            <form action="{{ route('tasks.store') }}" class="form grid gap-6" method="Post">
                @method('Post')
                @csrf
                <div class="grid gap-2">
                    <label for="task_description">Description</label>
                    <div class="grid grid-cols-[1fr_180px] gap-2">
                        <input type="text" id="task_description" name="description" placeholder="describe the task..."
                            tabindex="1" autofocus value="{{ old('description') }}">
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
                    <option value="all" {{ $status === 'all' || $status === 'all' ? 'selected' : '' }}>All</option>
                    <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
                <button type="submit" class="btn">Filter</button>
            </form>

            <ul id="task-list" class="grid gap-4" data-status="{{ $status }}">
                @forelse ($tasks as $task)
                @include('tasks.item', ['task' => $task, 'status' => $status])
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
    $(function() {
        var csrfToken = '{{ csrf_token() }}';
        var currentFilter = '{{ $status }}';

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function parseDate(value) {
            if (!value) {
                return null;
            }
            var date = new Date(value + 'T00:00:00');
            return isNaN(date.getTime()) ? null : date;
        }

        function isOverdue(value) {
            var date = parseDate(value);
            if (!date) {
                return false;
            }
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            return date < today;
        }

        function clearFieldErrors($form) {
            $form.find('.edit-field-error').text('').hide();
            $form.find('.edit-form-error').remove();
        }

        function displayFieldError($input, message) {
            var $error = $input.closest('label').find('.edit-field-error');
            if (!$error.length) {
                $error = $('<p class="edit-field-error text-red-500 text-sm mt-1"></p>');
                $input.after($error);
            }
            $error.text(message).show();
        }

        function displayFormError($form, message) {
            var $error = $form.find('.edit-form-error');
            if (!$error.length) {
                $error = $('<p class="edit-form-error text-red-500 text-sm mb-2"></p>');
                $form.prepend($error);
            }
            $error.text(message);
        }

        $('body').on('click', '.task-edit-button', function() {
            var $button = $(this);
            var $row = $button.closest('li');
            var $view = $row.find('.task-view');

            if ($view.find('.edit-task-form').length) {
                return;
            }

            var description = $row.data('task-description') || '';
            var dueDate = $row.data('task-due-date') || '';
            var updateUrl = $row.data('update-url');
            var status = currentFilter;

            var formHtml =
                '<form class="edit-task-form flex flex-col gap-3 w-full" method="POST" action="' +
                updateUrl + '">' +
                '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                '<input type="hidden" name="_method" value="PATCH">' +
                '<input type="hidden" name="status" value="' + status + '">' +
                '<div class="grid gap-3 md:grid-cols-2">' +
                '<label class="flex flex-col gap-1">' +
                '<span class="text-sm font-medium">Description</span>' +
                '<input type="text" name="description" class="input w-full" value="' + escapeHtml(
                    description) + '"  maxlength="255">' +
                '<p class="edit-field-error text-red-500 text-sm mt-1" style="display:none"></p>' +
                '</label>' +
                '<label class="flex flex-col gap-1">' +
                '<span class="text-sm font-medium">Due date</span>' +
                '<input type="date" name="due_date" class="input w-full" value="' + escapeHtml(
                    dueDate) + '">' +
                '<p class="edit-field-error text-red-500 text-sm mt-1" style="display:none"></p>' +
                '</label>' +
                '</div>' +
                '<div class="flex gap-2">' +
                '<button type="submit" class="btn">Save</button>' +
                '<button type="button" class="btn-sm-outline edit-cancel-button">Cancel</button>' +
                '</div>' +
                '</form>';

            $view.data('original-html', $view.html());
            $view.html(formHtml);
            $button.hide();
        });

        $('body').on('click', '.edit-cancel-button', function() {
            var $row = $(this).closest('li');
            var $view = $row.find('.task-view');

            if ($view.data('original-html')) {
                $view.html($view.data('original-html'));
            }
            $row.find('.task-edit-button').show();
        });

        $('body').on('submit', '.edit-task-form', function(event) {
            event.preventDefault();
            var $form = $(this);
            var $row = $form.closest('li');
            var $view = $row.find('.task-view');
            var description = $.trim($form.find('[name=description]').val());
            var dueDate = $.trim($form.find('[name=due_date]').val());
            var updateUrl = $form.attr('action');

            clearFieldErrors($form);

            if (!description) {
                displayFieldError($form.find('[name=description]'), 'Description is required.');
                return;
            }

            if (dueDate) {
                var parsed = parseDate(dueDate);
                if (!parsed) {
                    displayFieldError($form.find('[name=due_date]'), 'Due date must be a valid date.');
                    return;
                }
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                if (parsed <= today) {
                    displayFieldError($form.find('[name=due_date]'), 'Due date must be in the future.');
                    return;
                }
            }

            $.ajax({
                url: updateUrl,
                method: 'POST',
                data: $form.serialize(),
                success: function(response) {
                    $row.data('task-description', description);
                    $row.data('task-due-date', dueDate);

                    var dueText = dueDate ? new Date(dueDate).toLocaleDateString('en-CA')
                        .split('-').reverse().join('-') : 'No due date';
                    var descriptionHtml = '<div class="text-sm font-medium leading-none">' +
                        escapeHtml(description) + '</div>';
                    var dueHtml = '<div class="text-sm font-muted leading-none">' +
                        escapeHtml(dueText) + '</div>';

                    $view.html(descriptionHtml + dueHtml);
                    $row.find('.task-edit-button').show();

                    if (currentFilter === 'overdue' && !isOverdue(dueDate)) {
                        $row.remove();
                    }
                },
                error: function(xhr) {
                    var message = 'Unable to save changes.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        if (errors.description) {
                            displayFieldError($form.find('[name=description]'), errors
                                .description[0]);
                            return;
                        }
                        if (errors.due_date) {
                            displayFieldError($form.find('[name=due_date]'), errors
                                .due_date[0]);
                            return;
                        }
                    }
                    displayFormError($form, message);
                }
            });
        });
    });
    </script>
    @endsection
</x-tadieu-layout>