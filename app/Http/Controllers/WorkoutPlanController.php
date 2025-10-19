<?php

namespace App\Http\Controllers;

use App\Models\WorkoutPlan;
use App\Models\User;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    /**
     * Display a listing of workout plans.
     */
    public function index(Request $request)
    {
        $query = WorkoutPlan::with('user');

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by plan type if provided
        if ($request->has('plan_type') && $request->plan_type) {
            $query->where('plan_type', $request->plan_type);
        }

        // Filter by difficulty level if provided
        if ($request->has('difficulty_level') && $request->difficulty_level) {
            $query->where('difficulty_level', $request->difficulty_level);
        }

        // Show templates or user plans
        if ($request->has('show_templates') && $request->show_templates) {
            $query->where('is_template', true);
        } else {
            $query->where('is_template', false);
        }

        $workoutPlans = $query->orderBy('created_at', 'desc')->paginate(10);
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('workout-plans.index', compact('workoutPlans', 'users'));
    }

    /**
     * Show the form for creating a new workout plan.
     */
    public function create()
    {
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();
        return view('workout-plans.create', compact('users'));
    }

    /**
     * Store a newly created workout plan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'plan_type' => 'nullable|in:strength,cardio,hybrid,bodyweight,sport_specific,rehabilitation',
            'difficulty_level' => 'nullable|integer|min:1|max:5',
            'duration_weeks' => 'nullable|integer|min:1|max:52',
            'frequency_per_week' => 'nullable|integer|min:1|max:7',
            'is_active' => 'boolean',
            'is_template' => 'boolean',
        ]);

        $workoutPlan = WorkoutPlan::create($validated);

        return redirect()->route('workout-plans.show', $workoutPlan)
            ->with('success', 'Workout plan created successfully.');
    }

    /**
     * Display the specified workout plan.
     */
    public function show(WorkoutPlan $workoutPlan)
    {
        $workoutPlan->load(['user', 'planExercises.exerciseType']);
        $exercisesGrouped = $workoutPlan->getExercisesGrouped();
        return view('workout-plans.show', compact('workoutPlan', 'exercisesGrouped'));
    }

    /**
     * Show the form for editing the workout plan.
     */
    public function edit(WorkoutPlan $workoutPlan)
    {
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();
        return view('workout-plans.edit', compact('workoutPlan', 'users'));
    }

    /**
     * Update the specified workout plan.
     */
    public function update(Request $request, WorkoutPlan $workoutPlan)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'plan_type' => 'nullable|in:strength,cardio,hybrid,bodyweight,sport_specific,rehabilitation',
            'difficulty_level' => 'nullable|integer|min:1|max:5',
            'duration_weeks' => 'nullable|integer|min:1|max:52',
            'frequency_per_week' => 'nullable|integer|min:1|max:7',
            'is_active' => 'boolean',
            'is_template' => 'boolean',
        ]);

        $workoutPlan->update($validated);

        return redirect()->route('workout-plans.show', $workoutPlan)
            ->with('success', 'Workout plan updated successfully.');
    }

    /**
     * Remove the specified workout plan.
     */
    public function destroy(WorkoutPlan $workoutPlan)
    {
        $workoutPlan->delete();

        return redirect()->route('workout-plans.index')
            ->with('success', 'Workout plan deleted successfully.');
    }

    /**
     * Copy a workout plan template to a user.
     */
    public function copyTemplate(Request $request, WorkoutPlan $workoutPlan)
    {
        if (!$workoutPlan->is_template) {
            return redirect()->route('workout-plans.index')
                ->with('error', 'Only template plans can be copied.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
        ]);

        // Create new plan for user
        $newPlan = WorkoutPlan::create([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'description' => $workoutPlan->description,
            'plan_type' => $workoutPlan->plan_type,
            'difficulty_level' => $workoutPlan->difficulty_level,
            'duration_weeks' => $workoutPlan->duration_weeks,
            'frequency_per_week' => $workoutPlan->frequency_per_week,
            'is_active' => true,
            'is_template' => false,
        ]);

        // Copy all exercises
        foreach ($workoutPlan->planExercises as $exercise) {
            $newPlan->planExercises()->create([
                'exercise_type_id' => $exercise->exercise_type_id,
                'day_of_week' => $exercise->day_of_week,
                'week_number' => $exercise->week_number,
                'order_in_day' => $exercise->order_in_day,
                'sets' => $exercise->sets,
                'reps' => $exercise->reps,
                'weight_kg' => $exercise->weight_kg,
                'duration_seconds' => $exercise->duration_seconds,
                'rest_time_seconds' => $exercise->rest_time_seconds,
                'notes' => $exercise->notes,
            ]);
        }

        return redirect()->route('workout-plans.show', $newPlan)
            ->with('success', 'Workout plan copied successfully.');
    }

    /**
     * Show workout plan statistics.
     */
    public function statistics(Request $request)
    {
        $query = WorkoutPlan::query();

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $stats = [
            'total_plans' => $query->count(),
            'active_plans' => $query->where('is_active', true)->count(),
            'templates' => $query->where('is_template', true)->count(),
            'user_plans' => $query->where('is_template', false)->count(),
        ];

        $planTypes = $query->selectRaw('plan_type, COUNT(*) as count')
            ->groupBy('plan_type')
            ->get();

        $difficultyStats = $query->selectRaw('difficulty_level, COUNT(*) as count')
            ->groupBy('difficulty_level')
            ->orderBy('difficulty_level')
            ->get();

        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('workout-plans.statistics', compact('stats', 'planTypes', 'difficultyStats', 'users'));
    }
}
