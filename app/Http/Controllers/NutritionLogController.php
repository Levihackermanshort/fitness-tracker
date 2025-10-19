<?php

namespace App\Http\Controllers;

use App\Models\NutritionLog;
use App\Models\User;
use Illuminate\Http\Request;

class NutritionLogController extends Controller
{
    /**
     * Display a listing of nutrition logs.
     */
    public function index(Request $request)
    {
        $query = NutritionLog::with('user');

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by meal type if provided
        if ($request->has('meal_type') && $request->meal_type) {
            $query->where('meal_type', $request->meal_type);
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        $nutritionLogs = $query->orderBy('date', 'desc')->paginate(10);
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('nutrition-logs.index', compact('nutritionLogs', 'users'));
    }

    /**
     * Show the form for creating a new nutrition log.
     */
    public function create()
    {
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();
        $mealTypes = NutritionLog::getMealTypes();
        return view('nutrition-logs.create', compact('users', 'mealTypes'));
    }

    /**
     * Store a newly created nutrition log.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'meal_type' => 'nullable|in:breakfast,lunch,dinner,snack,pre_workout,post_workout',
            'food_name' => 'required|string|max:100',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:20',
            'calories' => 'nullable|numeric|min:0',
            'protein_g' => 'nullable|numeric|min:0',
            'carbs_g' => 'nullable|numeric|min:0',
            'fat_g' => 'nullable|numeric|min:0',
            'fiber_g' => 'nullable|numeric|min:0',
            'sugar_g' => 'nullable|numeric|min:0',
            'sodium_mg' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $nutritionLog = NutritionLog::create($validated);

        return redirect()->route('nutrition-logs.show', $nutritionLog)
            ->with('success', 'Nutrition log created successfully.');
    }

    /**
     * Display the specified nutrition log.
     */
    public function show(NutritionLog $nutritionLog)
    {
        $nutritionLog->load('user');
        return view('nutrition-logs.show', compact('nutritionLog'));
    }

    /**
     * Show the form for editing the nutrition log.
     */
    public function edit(NutritionLog $nutritionLog)
    {
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();
        $mealTypes = NutritionLog::getMealTypes();
        return view('nutrition-logs.edit', compact('nutritionLog', 'users', 'mealTypes'));
    }

    /**
     * Update the specified nutrition log.
     */
    public function update(Request $request, NutritionLog $nutritionLog)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'meal_type' => 'nullable|in:breakfast,lunch,dinner,snack,pre_workout,post_workout',
            'food_name' => 'required|string|max:100',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:20',
            'calories' => 'nullable|numeric|min:0',
            'protein_g' => 'nullable|numeric|min:0',
            'carbs_g' => 'nullable|numeric|min:0',
            'fat_g' => 'nullable|numeric|min:0',
            'fiber_g' => 'nullable|numeric|min:0',
            'sugar_g' => 'nullable|numeric|min:0',
            'sodium_mg' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $nutritionLog->update($validated);

        return redirect()->route('nutrition-logs.show', $nutritionLog)
            ->with('success', 'Nutrition log updated successfully.');
    }

    /**
     * Remove the specified nutrition log.
     */
    public function destroy(NutritionLog $nutritionLog)
    {
        $nutritionLog->delete();

        return redirect()->route('nutrition-logs.index')
            ->with('success', 'Nutrition log deleted successfully.');
    }

    /**
     * Show daily nutrition summary.
     */
    public function dailySummary(Request $request)
    {
        $userId = $request->get('user_id');
        $date = $request->get('date', now()->toDateString());

        if (!$userId) {
            return redirect()->route('nutrition-logs.index')
                ->with('error', 'Please select a user to view daily summary.');
        }

        $user = User::findOrFail($userId);
        $dailyTotals = NutritionLog::getDailyTotals($userId, $date);
        $mealLogs = NutritionLog::where('user_id', $userId)
            ->where('date', $date)
            ->orderBy('created_at')
            ->get();

        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('nutrition-logs.daily-summary', compact('user', 'dailyTotals', 'mealLogs', 'users', 'date'));
    }

    /**
     * Show nutrition statistics.
     */
    public function statistics(Request $request)
    {
        $query = NutritionLog::query();

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        $stats = [
            'total_entries' => $query->count(),
            'total_calories' => $query->sum('calories'),
            'total_protein' => $query->sum('protein_g'),
            'total_carbs' => $query->sum('carbs_g'),
            'total_fat' => $query->sum('fat_g'),
            'avg_daily_calories' => $query->selectRaw('AVG(calories) as avg_calories')->value('avg_calories'),
        ];

        $mealTypes = $query->selectRaw('meal_type, COUNT(*) as count, SUM(calories) as total_calories')
            ->groupBy('meal_type')
            ->get();

        $topFoods = $query->selectRaw('food_name, COUNT(*) as count, SUM(calories) as total_calories')
            ->groupBy('food_name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('nutrition-logs.statistics', compact('stats', 'mealTypes', 'topFoods', 'users'));
    }
}
