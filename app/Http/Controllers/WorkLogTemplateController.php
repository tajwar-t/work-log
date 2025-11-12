<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkLogTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WorkLogTemplateController extends Controller
{
    // Show generator page
    public function index()
    {
        return view('work_logs.generator');
    }

    // AJAX: Generate template
    public function generateAjax(Request $request)
    {
        $request->validate([
            'template_type' => 'required|string|in:day_start,day_end',
            'log_date' => 'required|date',
        ]);

        $templateType = $request->input('template_type');
        $date = Carbon::parse($request->input('log_date'))->format('d/m/Y');

        // Fetch inputs
        $dayStartLastDay = array_filter($request->input('day_start_last_day', []));
        $dayStartToday = array_filter($request->input('day_start_today', []));
        $dayEndToday = array_filter($request->input('day_end_today', []));
        $dayEndTomorrow = array_filter($request->input('day_end_tomorrow', []));

        // Bold divider
        $divider = '<strong>— -- — -- — -- — -- — -- —</strong>';

        $template = '';
        $template .= $divider . "<br>";
        $template .= "<strong>" . ($templateType === 'day_start' ? "Day Start $date" : "Day End $date") . "</strong><br>";
        $template .= $divider . "<br>";

        if ($templateType === 'day_start') {
            $template .= "<strong>::: Last day I worked with :::::</strong><br>";
            foreach ($dayStartLastDay as $i => $item) {
                $template .= ($i + 1) . ". " . htmlspecialchars($item) . "<br>";
            }
            $template .= $divider . "<br>";
            $template .= "<strong>:::: Today I will work with :::::</strong><br>";
            foreach ($dayStartToday as $i => $item) {
                $template .= ($i + 1) . ". " . htmlspecialchars($item) . "<br>";
            }
            $template .= $divider . "<br>";
        } else { // day_end
            $template .= "<strong>::: Today I worked with :::::</strong><br>";
            foreach ($dayEndToday as $i => $item) {
                $template .= ($i + 1) . ". " . htmlspecialchars($item) . "<br>";
            }
            $template .= $divider . "<br>";
            $template .= "<strong>:::: Tomorrow I will work with :::::</strong><br>";
            foreach ($dayEndTomorrow as $i => $item) {
                $template .= ($i + 1) . ". " . htmlspecialchars($item) . "<br>";
            }
            $template .= $divider . "<br>";
        }

        // Save or replace existing template
        $log = WorkLogTemplate::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'log_date' => $request->input('log_date'),
                'template_type' => $templateType,
            ],
            [
                'content' => $template,
                'day_start_last_day' => $templateType === 'day_start' ? implode("\n",$dayStartLastDay) : null,
                'day_start_today' => $templateType === 'day_start' ? implode("\n",$dayStartToday) : null,
                'day_end_today' => $templateType === 'day_end' ? implode("\n",$dayEndToday) : null,
                'day_end_tomorrow' => $templateType === 'day_end' ? implode("\n",$dayEndTomorrow) : null,
            ]
        );

        return response()->json([
            'success' => true,
            'template' => $template,
            'id' => $log->id,
        ]);
    }

    // Show history page
    public function history()
    {
        $templates = WorkLogTemplate::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('work_logs.history', compact('templates'));
    }

    // AJAX: Fetch previous day's work for smart suggestions
public function fetchPreviousWork(Request $request)
{
    $templateType = $request->input('template_type'); // day_start or day_end
    $logDate = $request->input('log_date'); // YYYY-MM-DD
    $userId = Auth::id();

    $data = [
        'lastDayWork' => [],
        'todayWork' => [],
        'tomorrowWork' => []
    ];

    if ($templateType === 'day_start') {
        // ONLY fetch the immediately previous day's Day End
        $prevDate = \Carbon\Carbon::parse($logDate)->subDay()->format('Y-m-d');

        $prevDayEnd = WorkLogTemplate::where('user_id', $userId)
            ->where('log_date', $prevDate)
            ->where('template_type', 'day_end')
            ->latest('created_at')
            ->first();

        if ($prevDayEnd) {
            $data['lastDayWork'] = $prevDayEnd->day_end_today 
                ? explode("\n", $prevDayEnd->day_end_today) 
                : [];
            $data['todayWork'] = $prevDayEnd->day_end_tomorrow 
                ? explode("\n", $prevDayEnd->day_end_tomorrow) 
                : [];
        }

    } elseif ($templateType === 'day_end') {
        // ONLY fetch the same day's Day Start
        $dayStart = WorkLogTemplate::where('user_id', $userId)
            ->where('log_date', $logDate)
            ->where('template_type', 'day_start')
            ->latest('created_at')
            ->first();

        if ($dayStart) {
            $data['todayWork'] = $dayStart->day_start_today 
                ? explode("\n", $dayStart->day_start_today) 
                : [];
        }

        // Tomorrow's Work remains empty until user fills
        $data['tomorrowWork'] = [];
    }

    return response()->json($data);
}



public function saveAjax(Request $request)
{
    $request->validate([
        'template_type' => 'required|string|in:day_start,day_end',
        'log_date' => 'required|date',
    ]);

    $templateType = $request->input('template_type');

    $lastDayWork = array_filter($request->input('day_start_last_day', []));
    $todayWork = array_filter($request->input('day_start_today', []));
    $dayEndToday = array_filter($request->input('day_end_today', []));
    $tomorrowWork = array_filter($request->input('day_end_tomorrow', []));

    // Build content placeholder (optional)
    $content = "Draft saved"; // Can keep empty or show "Draft"

    // Save or update existing entry
    WorkLogTemplate::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'log_date' => $request->input('log_date'),
            'template_type' => $templateType,
        ],
        [
            'content' => $content,
            'day_start_last_day' => $templateType === 'day_start' ? implode("\n",$lastDayWork) : null,
            'day_start_today' => $templateType === 'day_start' ? implode("\n",$todayWork) : null,
            'day_end_today' => $templateType === 'day_end' ? implode("\n",$dayEndToday) : null,
            'day_end_tomorrow' => $templateType === 'day_end' ? implode("\n",$tomorrowWork) : null,
        ]
    );

    return response()->json(['success' => true]);
}



}
