<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{

    public function index()
    {
        return view('activity-calendar.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:workout,rest,goal,running,gym,yoga,cardio,stretching,cycling,swimming,weightlifting,pilates,hiking,boxing,dance,crossfit,walking,meditation,tennis,basketball,soccer,climbing,rowing,martial_arts,recovery',
            'description' => 'nullable|string|max:255',
        ]);

        $event = Calendar::create([
            'user_id' => Auth::id(),
            'date' => Carbon::parse($validated['date'])->toDateString(),
            'type' => $validated['type'],
            'description' => $validated['description'],
            'completed' => false,
        ]);

        return response()->json([
            'success' => true,
            'event' => [
                'id' => $event->id,
                'start' => $event->date,
                'title' => ucfirst($event->type).': '.($event->description ?? 'No description'),
                'type' => $event->type,
                'completed' => $event->completed,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $calendar = Calendar::findOrFail($id);

        if ($calendar->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'completed' => 'required|boolean',
        ]);

        $calendar->update($validated);

        return response()->json(['success' => true]);
    }

    public function getEvents(Request $request)
    {
        $start = $request->query('start', Carbon::today()->toDateString());
        $end = $request->query('end', Carbon::today()->addDays(30)->toDateString());

        $events = Calendar::where('user_id', Auth::id())
            ->whereBetween('date', [$start, $end])
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'start' => $event->date->toDateString(),
                    'title' => ucfirst($event->type).': '.($event->description ?? 'No description'),
                    'type' => $event->type,
                    'description' => $event->description,
                    'completed' => $event->completed,
                ];
            });

        return response()->json($events);
    }

    public function destroy($id)
    {
        $calendar = Calendar::findOrFail($id);

        if ($calendar->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $calendar->delete();

        return response()->json(['success' => true]);
    }
}
