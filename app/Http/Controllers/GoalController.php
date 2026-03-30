<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\GoalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{

    public function index()
    {
        $goals = Goal::where('user_id', Auth::id())->get();

        return view('goals.index', compact('goals'));
    }

    public function create()
    {
        return view('goals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'target_value' => 'required|numeric|min:0',
            'end_date' => 'required|date|after:today',
            'description' => 'nullable|string|max:255',
        ]);

        Goal::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'target_value' => $request->target_value,
            'description' => $request->description ?? '',
            'end_date' => $request->end_date,
            'current_value' => 0,
        ]);

        return redirect()->route('goals.index')->with('success', '');
    }

    public function edit(Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('goals.edit', compact('goal'));
    }

    public function update(Request $request, Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'type' => 'required|string',
            'target_value' => 'required|numeric|min:0',
            'end_date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $goal->update([
            'type' => $request->type,
            'target_value' => $request->target_value,
            'description' => $request->description ?? '',
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('goals.index')->with('success', 'Goal updated successfully');
    }

    public function destroy(Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Goal deleted successfully');
    }

    public function log(Goal $goal)
    {

        if ($goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('goals.log', compact('goal'));
    }

    public function storeLog(Request $request, Goal $goal)
    {

        if ($goal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'value' => 'required|numeric|min:0',
        ]);

        $change = $request->value - $goal->current_value;

        GoalLog::create([
            'goal_id' => $goal->id,
            'value' => $request->value,
            'change' => $change,
            'date' => now()->toDateString(),
        ]);

        $goal->current_value = $request->value;
        $goal->save();

        $sessionKey = 'goal_'.$goal->id.'_completed';
        if ($goal->current_value >= $goal->target_value && ! session()->has($sessionKey)) {
            session()->put($sessionKey, true);
            session()->flash('goal_completed', true);
        }

        return redirect()->route('goals.index')->with('success', '');
    }
}
