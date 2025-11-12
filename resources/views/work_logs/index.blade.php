@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">My Work Logs</h1>
    <a href="{{ route('work-logs.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Add Log</a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</div>
@endif

<table class="min-w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-200">
            <th class="py-2 px-4">Date</th>
            <th class="py-2 px-4">Start</th>
            <th class="py-2 px-4">End</th>
            <th class="py-2 px-4">Work Done</th>
            <th class="py-2 px-4">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($logs as $log)
            <tr class="border-t">
                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($log->log_date)->format('Y-m-d') }}</td>
                <td class="py-2 px-4">{{ $log->start_time }}</td>
                <td class="py-2 px-4">{{ $log->end_time }}</td>
                <td class="py-2 px-4 whitespace-pre-line">{{ $log->work_done }}</td>
                <td class="py-2 px-4 flex space-x-2">
                    <a href="{{ route('work-logs.edit', $log) }}" class="bg-blue-500 text-white px-2 py-1 rounded">Edit</a>
                    <form method="POST" action="{{ route('work-logs.destroy', $log) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded"
                                onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-4">No work logs found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
