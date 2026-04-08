<x-tadieu-layout>
    @section('content')
    <div class="card w-full">
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

        
    </div>
    @endsection
</x-tadieu-layout>