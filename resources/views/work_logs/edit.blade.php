@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">{{ isset($work_log) ? 'Edit' : 'Add' }} Work Log</h1>

<form action="{{ isset($work_log) ? route('work-logs.update', $work_log) : route('work-logs.store') }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf
    @if(isset($work_log))
        @method('PUT')
    @endif

    <div class="mb-4">
        <label class="block font-semibold mb-1">Date</label>
        <input type="date" name="log_date" value="{{ old('log_date', $work_log->log_date ?? '') }}" class="w-full border p-2 rounded">
        @error('log_date') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4 flex gap-4">
        <div class="flex-1">
            <label class="block font-semibold mb-1">Start Time</label>
            <input type="time" name="start_time" value="{{ old('start_time', $work_log->start_time ?? '') }}" class="w-full border p-2 rounded">
            @error('start_time') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
        <div class="flex-1">
            <label class="block font-semibold mb-1">End Time</label>
            <input type="time" name="end_time" value="{{ old('end_time', $work_log->end_time ?? '') }}" class="w-full border p-2 rounded">
            @error('end_time') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="mb-4">
        <label class="block font-semibold mb-1">Work Done</label>
        <textarea name="work_done" rows="5" class="w-full border p-2 rounded" placeholder="List your tasks, one per line">{{ old('work_done', $work_log->work_done ?? '') }}</textarea>
        @error('work_done') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">{{ isset($work_log) ? 'Update' : 'Create' }}</button>
</form>
@endsection
