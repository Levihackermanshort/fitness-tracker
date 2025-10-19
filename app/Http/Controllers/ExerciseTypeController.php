<?php

namespace App\Http\Controllers;

use App\Models\ExerciseType;
use Illuminate\Http\Request;

class ExerciseTypeController extends Controller
{
    /**
     * Display a listing of exercise types.
     */
    public function index(Request $request)
    {
        $query = ExerciseType::query();

        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by difficulty level if provided
        if ($request->has('difficulty_level') && $request->difficulty_level) {
            $query->where('difficulty_level', $request->difficulty_level);
        }

        // Filter by muscle group if provided
        if ($request->has('muscle_group') && $request->muscle_group) {
            $query->whereJsonContains('muscle_groups', $request->muscle_group);
        }

        // Only show active exercises by default
        if (!$request->has('include_inactive')) {
            $query->where('is_active', true);
        }

        $exerciseTypes = $query->orderBy('name')->paginate(15);
        
        $categories = ExerciseType::distinct()->pluck('category')->sort();
        $muscleGroups = ExerciseType::whereNotNull('muscle_groups')
            ->get()
            ->pluck('muscle_groups')
            ->flatten()
            ->unique()
            ->sort();

        return view('exercise-types.index', compact('exerciseTypes', 'categories', 'muscleGroups'));
    }

    /**
     * Show the form for creating a new exercise type.
     */
    public function create()
    {
        return view('exercise-types.create');
    }

    /**
     * Store a newly created exercise type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'muscle_groups' => 'nullable|array',
            'muscle_groups.*' => 'string|max:50',
            'equipment_needed' => 'nullable|string|max:100',
            'difficulty_level' => 'nullable|integer|min:1|max:5',
            'calories_per_minute' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'video_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);

        $exerciseType = ExerciseType::create($validated);

        return redirect()->route('exercise-types.show', $exerciseType)
            ->with('success', 'Exercise type created successfully.');
    }

    /**
     * Display the specified exercise type.
     */
    public function show(ExerciseType $exerciseType)
    {
        $exerciseType->load(['workoutExercises.workoutSession.user', 'planExercises.workoutPlan.user']);
        return view('exercise-types.show', compact('exerciseType'));
    }

    /**
     * Show the form for editing the exercise type.
     */
    public function edit(ExerciseType $exerciseType)
    {
        return view('exercise-types.edit', compact('exerciseType'));
    }

    /**
     * Update the specified exercise type.
     */
    public function update(Request $request, ExerciseType $exerciseType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'muscle_groups' => 'nullable|array',
            'muscle_groups.*' => 'string|max:50',
            'equipment_needed' => 'nullable|string|max:100',
            'difficulty_level' => 'nullable|integer|min:1|max:5',
            'calories_per_minute' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'video_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);

        $exerciseType->update($validated);

        return redirect()->route('exercise-types.show', $exerciseType)
            ->with('success', 'Exercise type updated successfully.');
    }

    /**
     * Remove the specified exercise type.
     */
    public function destroy(ExerciseType $exerciseType)
    {
        $exerciseType->delete();

        return redirect()->route('exercise-types.index')
            ->with('success', 'Exercise type deleted successfully.');
    }

    /**
     * Show exercise type statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_exercises' => ExerciseType::count(),
            'active_exercises' => ExerciseType::where('is_active', true)->count(),
            'categories_count' => ExerciseType::distinct()->count('category'),
        ];

        $categoryStats = ExerciseType::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        $difficultyStats = ExerciseType::selectRaw('difficulty_level, COUNT(*) as count')
            ->groupBy('difficulty_level')
            ->orderBy('difficulty_level')
            ->get();

        $mostUsedExercises = ExerciseType::withCount('workoutExercises')
            ->orderBy('workout_exercises_count', 'desc')
            ->limit(10)
            ->get();

        return view('exercise-types.statistics', compact('stats', 'categoryStats', 'difficultyStats', 'mostUsedExercises'));
    }
}
