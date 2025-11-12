<?php

namespace App\Http\Controllers;

use App\Models\WorkLog;
use Illuminate\Http\Request;

class WorkLogController extends Controller
{
    public function index()
    {
        $logs = auth()->user()->workLogs()->orderBy('log_date', 'desc')->get();
        return view('work_logs.index', compact('logs'));
    }

    public function create()
    {
        return view('work_logs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'log_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'work_done' => 'required|string',
        ]);

        $data['user_id'] = auth()->id();

        WorkLog::create($data);

        return redirect()->route('work-logs.index')->with('success', 'Work log created!');
    }

    public function edit(WorkLog $work_log)
    {
        if ($work_log->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
        return view('work_logs.edit', compact('work_log'));
    }

    public function update(Request $request, WorkLog $work_log)
    {
        if ($work_log->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'log_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'work_done' => 'required|string',
        ]);

        $work_log->update($data);

        return redirect()->route('work-logs.index')->with('success', 'Work log updated!');
    }

    public function destroy(WorkLog $work_log)
    {
        if ($work_log->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        $work_log->delete();
        return redirect()->route('work-logs.index')->with('success', 'Work log deleted!');
    }
}
