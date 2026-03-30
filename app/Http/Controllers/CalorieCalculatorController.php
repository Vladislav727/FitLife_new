<?php

namespace App\Http\Controllers;

use App\Models\MealLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalorieCalculatorController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $todayCalories = $this->getTodayCalories($user->id);

        return view('calories.index', compact('user', 'todayCalories'));
    }

    public function calculate(Request $request)
    {

        $data = $request->validate([
            'weight' => 'required|numeric|min:30|max:300',
            'height' => 'required|numeric|min:100|max:250',
            'age' => 'required|numeric|min:10|max:100',
            'activity_level' => 'required|in:sedentary,light,moderate,active',
            'goal_type' => 'required|in:lose_weight,maintain,gain_weight',
        ]);

        $bmr = 10 * $data['weight'] + 6.25 * $data['height'] - 5 * $data['age'] + 5;

        $activityFactor = match ($data['activity_level']) {
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'active' => 1.725,
        };

        $tdee = $bmr * $activityFactor;

        $calories = match ($data['goal_type']) {
            'lose_weight' => $tdee - 500,
            'gain_weight' => $tdee + 500,
            default => $tdee
        };

        $protein = round($data['weight'] * 1.8);
        $fat = round(($calories * 0.25) / 9);
        $carbs = round(($calories - ($protein * 4 + $fat * 9)) / 4);

        $user = Auth::user();
        $todayCalories = $this->getTodayCalories($user->id);

        return view('calories.index', compact(
            'user', 'calories', 'protein', 'fat', 'carbs', 'todayCalories'
        ) + ['goal' => $data['goal_type']]);
    }

    private function getTodayCalories(int $userId): int
    {
        return MealLog::where('user_id', $userId)
            ->whereDate('created_at', now())
            ->sum('calories');
    }
}
