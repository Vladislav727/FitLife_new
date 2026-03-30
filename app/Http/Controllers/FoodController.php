<?php

namespace App\Http\Controllers;

use App\Models\MealLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FoodController extends Controller
{

    private array $fallbackFoods = [
        'rice' => ['calories' => 130, 'protein' => 2.7, 'fat' => 0.3, 'carbs' => 28.2],
        'chicken breast' => ['calories' => 165, 'protein' => 31.0, 'fat' => 3.6, 'carbs' => 0.0],
        'chicken' => ['calories' => 239, 'protein' => 27.3, 'fat' => 13.6, 'carbs' => 0.0],
        'beef' => ['calories' => 250, 'protein' => 26.0, 'fat' => 15.0, 'carbs' => 0.0],
        'pork' => ['calories' => 242, 'protein' => 27.3, 'fat' => 14.0, 'carbs' => 0.0],
        'salmon' => ['calories' => 208, 'protein' => 20.4, 'fat' => 13.4, 'carbs' => 0.0],
        'egg' => ['calories' => 155, 'protein' => 13.0, 'fat' => 11.0, 'carbs' => 1.1],
        'milk' => ['calories' => 61, 'protein' => 3.2, 'fat' => 3.3, 'carbs' => 4.8],
        'cheese' => ['calories' => 402, 'protein' => 25.0, 'fat' => 33.0, 'carbs' => 1.3],
        'bread' => ['calories' => 265, 'protein' => 9.0, 'fat' => 3.2, 'carbs' => 49.0],
        'oatmeal' => ['calories' => 68, 'protein' => 2.4, 'fat' => 1.4, 'carbs' => 12.0],
        'banana' => ['calories' => 89, 'protein' => 1.1, 'fat' => 0.3, 'carbs' => 22.8],
        'apple' => ['calories' => 52, 'protein' => 0.3, 'fat' => 0.2, 'carbs' => 13.8],
        'potato' => ['calories' => 77, 'protein' => 2.0, 'fat' => 0.1, 'carbs' => 17.5],
        'pasta' => ['calories' => 131, 'protein' => 5.0, 'fat' => 1.1, 'carbs' => 25.0],
        'yogurt' => ['calories' => 59, 'protein' => 10.0, 'fat' => 0.7, 'carbs' => 3.6],
    ];

    public function index(Request $request)
    {
        $logs = MealLog::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $todayLogs = MealLog::where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->get();

        $todaySummary = [
            'calories' => $todayLogs->sum('calories'),
            'protein' => round($todayLogs->sum('protein'), 1),
            'fats' => round($todayLogs->sum('fats'), 1),
            'carbs' => round($todayLogs->sum('carbs'), 1),
            'count' => $todayLogs->count(),
        ];

        return view('foods.index', [
            'mealLogs' => $logs,
            'todaySummary' => $todaySummary,
        ]);
    }

    public function lookup(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:200',
        ]);

        $query = $request->input('query');
        $apiKey = config('services.calorieninjas.key');
        $items = [];

        if ($apiKey) {
            try {
                $response = Http::withHeaders([
                    'X-Api-Key' => $apiKey,
                ])->timeout(5)->get('https://api.calorieninjas.com/v1/nutrition', [
                    'query' => $query,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    foreach ($data['items'] ?? [] as $item) {
                        $items[] = [
                            'name' => $item['name'] ?? $query,
                            'calories' => round($item['calories'] ?? 0),
                            'serving_size' => round($item['serving_size_g'] ?? 100),
                            'protein' => round($item['protein_g'] ?? 0, 1),
                            'fat' => round($item['fat_total_g'] ?? 0, 1),
                            'carbs' => round($item['carbohydrates_total_g'] ?? 0, 1),
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::warning('CalorieNinjas API error: '.$e->getMessage());
            }
        }

        if (empty($items)) {
            $items = $this->fallbackLookup($query);
        }

        return response()->json([
            'success' => ! empty($items),
            'items' => $items,
            'source' => $apiKey && ! empty($items) ? 'api' : 'local',
        ]);
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.food' => 'required|string|max:200',
            'items.*.quantity' => 'required|numeric|min:1|max:10000',
            'items.*.meal' => 'required|string|in:Breakfast,Lunch,Dinner,Snack',
            'items.*.calories_per_serving' => 'nullable|numeric|min:0',
            'items.*.serving_size' => 'nullable|numeric|min:1',
            'items.*.protein' => 'nullable|numeric|min:0',
            'items.*.fat' => 'nullable|numeric|min:0',
            'items.*.carbs' => 'nullable|numeric|min:0',
        ]);

        $createdLogs = [];
        $totalCalories = 0;
        $totalProtein = 0;
        $totalFats = 0;
        $totalCarbs = 0;

        foreach ($request->input('items') as $item) {
            $food = $item['food'];
            $quantity = (float) $item['quantity'];
            $servingSize = (float) ($item['serving_size'] ?? 100);
            $ratio = $quantity / max($servingSize, 1);

            $calories = round(($item['calories_per_serving'] ?? 0) * $ratio);
            $protein = round(($item['protein'] ?? 0) * $ratio, 1);
            $fats = round(($item['fat'] ?? 0) * $ratio, 1);
            $carbs = round(($item['carbs'] ?? 0) * $ratio, 1);

            if (empty($item['calories_per_serving'])) {
                $localData = $this->getLocalNutrition($food);
                if ($localData) {
                    $ratio100 = $quantity / 100;
                    $calories = round($localData['calories'] * $ratio100);
                    $protein = round($localData['protein'] * $ratio100, 1);
                    $fats = round($localData['fat'] * $ratio100, 1);
                    $carbs = round($localData['carbs'] * $ratio100, 1);
                }
            }

            $totalCalories += $calories;
            $totalProtein += $protein;
            $totalFats += $fats;
            $totalCarbs += $carbs;

            $createdLogs[] = MealLog::create([
                'user_id' => Auth::id(),
                'meal' => $item['meal'],
                'food' => $food,
                'quantity' => $quantity,
                'calories' => $calories,
                'protein' => $protein,
                'fats' => $fats,
                'carbs' => $carbs,
            ]);
        }

        if (empty($createdLogs)) {
            return response()->json([
                'success' => false,
                'message' => __('food.no_valid_meals'),
            ], 422);
        }

        $todayLogs = MealLog::where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->get();

        $dailyCalories = $todayLogs->sum('calories');

        $comment = match (true) {
            $dailyCalories < 1500 => __('food.eat_more'),
            $dailyCalories < 2500 => __('food.great_job'),
            default => __('food.too_much'),
        };

        return response()->json([
            'success' => true,
            'added' => [
                'calories' => round($totalCalories),
                'protein' => round($totalProtein, 1),
                'fats' => round($totalFats, 1),
                'carbs' => round($totalCarbs, 1),
            ],
            'daily' => [
                'calories' => round($dailyCalories),
                'protein' => round($todayLogs->sum('protein'), 1),
                'fats' => round($todayLogs->sum('fats'), 1),
                'carbs' => round($todayLogs->sum('carbs'), 1),
            ],
            'comment' => $comment,
            'logs' => collect($createdLogs)->map(fn ($log) => [
                'id' => $log->id,
                'meal' => $log->meal,
                'food' => $log->food,
                'quantity' => $log->quantity,
                'calories' => $log->calories,
                'protein' => $log->protein,
                'fats' => $log->fats,
                'carbs' => $log->carbs,
                'created_at' => $log->created_at->format('M d, Y H:i'),
            ]),
        ]);
    }

    public function history()
    {
        $logs = MealLog::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('foods.history', ['mealLogs' => $logs]);
    }

    public function destroy(MealLog $mealLog)
    {
        if ($mealLog->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $mealLog->delete();

        return response()->json(['success' => true]);
    }

    private function fallbackLookup(string $query): array
    {
        $query = mb_strtolower(trim($query));
        $items = [];

        foreach ($this->fallbackFoods as $name => $data) {
            if (str_contains($query, $name) || str_contains($name, $query)) {
                $items[] = [
                    'name' => ucfirst($name),
                    'calories' => $data['calories'],
                    'serving_size' => 100,
                    'protein' => $data['protein'],
                    'fat' => $data['fat'],
                    'carbs' => $data['carbs'],
                ];
            }
        }

        return $items;
    }

    private function getLocalNutrition(string $food): ?array
    {
        $food = mb_strtolower(trim($food));

        foreach ($this->fallbackFoods as $name => $data) {
            if (str_contains($food, $name) || str_contains($name, $food)) {
                return $data;
            }
        }

        return null;
    }
}
