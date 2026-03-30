<?php

namespace App\Http\Controllers;

use App\Models\Sleep;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SleepController extends Controller
{

    public function index()
    {
        $query = Sleep::where('user_id', Auth::id());

        $average = (clone $query)->avg('duration');

        $sleeps = $query->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        $today = Carbon::today()->toDateString();
        $todaySleeps = $sleeps->filter(fn ($s) => $s->date === $today);
        $todayDuration = round($todaySleeps->sum('duration'), 1);
        $recommendedHours = 8;

        return view('sleep.index', compact('sleeps', 'average', 'todaySleeps', 'todayDuration', 'recommendedHours'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $start = Carbon::parse("{$request->date} {$request->start_time}");
        $end = Carbon::parse("{$request->date} {$request->end_time}");

        if ($end->lt($start)) {
            $end->addDay();
        }

        $duration = $start->diffInMinutes($end) / 60;

        Sleep::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration' => $duration,
        ]);

        return redirect()->back()->with('success', ' ');
    }
}
