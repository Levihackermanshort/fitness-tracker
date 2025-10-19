<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkoutSession;
use App\Models\Goal;
use App\Models\NutritionLog;
use App\Models\BodyMeasurement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard.
     */
    public function index(Request $request)
    {
        $userId = $request->get('user_id');
        
        if (!$userId) {
            // Show general statistics if no user selected
            $stats = [
                'total_users' => User::count(),
                'total_workouts' => WorkoutSession::count(),
                'total_goals' => Goal::count(),
                'total_nutrition_logs' => NutritionLog::count(),
                'total_body_measurements' => BodyMeasurement::count(),
            ];

            $recentWorkouts = WorkoutSession::with('user')
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();

            $recentGoals = Goal::with('user')
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $users = User::select('id', 'username', 'first_name', 'last_name')->get();

            // Add empty collections for user-specific variables to avoid errors
            $user = null;
            $activeGoals = collect();
            $recentNutritionLogs = collect();
            $latestMeasurements = collect();

            return view('dashboard.index', compact('stats', 'recentWorkouts', 'recentGoals', 'users', 'user', 'activeGoals', 'recentNutritionLogs', 'latestMeasurements'));
        }

        // Show user-specific dashboard
        $user = User::findOrFail($userId);
        
        $stats = [
            'total_workouts' => $user->workoutSessions()->count(),
            'total_calories' => $user->workoutSessions()->sum('total_calories'),
            'total_duration' => $user->workoutSessions()->sum('total_duration'),
            'active_goals' => $user->goals()->where('is_active', true)->count(),
            'achieved_goals' => $user->goals()->where('is_achieved', true)->count(),
            'latest_weight' => $user->bodyMeasurements()->latest('measurement_date')->value('weight_kg'),
            'latest_bmi' => $user->bodyMeasurements()->latest('measurement_date')->value('bmi'),
        ];

        $recentWorkouts = $user->workoutSessions()
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        $activeGoals = $user->goals()
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get();

        $recentNutritionLogs = $user->nutritionLogs()
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        $latestMeasurements = $user->bodyMeasurements()
            ->orderBy('measurement_date', 'desc')
            ->limit(3)
            ->get();

        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('dashboard.index', compact('user', 'stats', 'recentWorkouts', 'activeGoals', 'recentNutritionLogs', 'latestMeasurements', 'users'));
    }

    /**
     * Show workout analytics.
     */
    public function workoutAnalytics(Request $request)
    {
        $userId = $request->get('user_id');
        $query = WorkoutSession::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        $stats = [
            'total_workouts' => $query->count(),
            'total_duration' => $query->sum('total_duration'),
            'total_calories' => $query->sum('total_calories'),
            'avg_duration' => $query->avg('total_duration'),
            'avg_calories' => $query->avg('total_calories'),
            'avg_exertion' => $query->avg('perceived_exertion'),
            'avg_mood_improvement' => $query->selectRaw('AVG(mood_after - mood_before) as avg_improvement')->value('avg_improvement'),
        ];

        $workoutTypes = $query->selectRaw('workout_type, COUNT(*) as count')
            ->groupBy('workout_type')
            ->get();

        $monthlyStats = $query->selectRaw('DATE_TRUNC(\'month\', date) as month, COUNT(*) as count, SUM(total_duration) as total_duration, SUM(total_calories) as total_calories')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('dashboard.workout-analytics', compact('stats', 'workoutTypes', 'monthlyStats', 'users'));
    }

    /**
     * Show nutrition analytics.
     */
    public function nutritionAnalytics(Request $request)
    {
        $userId = $request->get('user_id');
        $query = NutritionLog::query();

        if ($userId) {
            $query->where('user_id', $userId);
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

        return view('dashboard.nutrition-analytics', compact('stats', 'mealTypes', 'topFoods', 'users'));
    }

    /**
     * Show goal analytics.
     */
    public function goalAnalytics(Request $request)
    {
        $userId = $request->get('user_id');
        $query = Goal::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $stats = [
            'total_goals' => $query->count(),
            'active_goals' => $query->where('is_active', true)->where('is_achieved', false)->count(),
            'achieved_goals' => $query->where('is_achieved', true)->count(),
            'overdue_goals' => $query->where('is_active', true)
                ->where('is_achieved', false)
                ->where('target_date', '<', now()->toDateString())
                ->count(),
        ];

        $goalTypes = $query->selectRaw('goal_type, COUNT(*) as count')
            ->groupBy('goal_type')
            ->get();

        $priorityDistribution = $query->selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->orderBy('priority')
            ->get();

        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('dashboard.goal-analytics', compact('stats', 'goalTypes', 'priorityDistribution', 'users'));
    }
}
