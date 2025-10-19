<?php

namespace App\Http\Controllers;

use App\Models\WorkoutExercise;
use App\Models\WorkoutSession;
use App\Models\ExerciseType;
use Illuminate\Http\Request;

class WorkoutExerciseController extends Controller
{
    /**
     * Display a listing of workout exercises.
     */
    public function index(Request $request)
    {
        $query = WorkoutExercise::with(['workoutSession.user', 'exerciseType']);

        // Filter by workout session if provided
        if ($request->has('workout_session_id') && $request->workout_session_id) {
            $query->where('workout_session_id', $request->workout_session_id);
        }

        // Filter by exercise type if provided
        if ($request->has('exercise_type_id') && $request->exercise_type_id) {
            $query->where('exercise_type_id', $request->exercise_type_id);
        }

        $workoutExercises = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $workoutSessions = WorkoutSession::with('user')->orderBy('date', 'desc')->limit(50)->get();
        $exerciseTypes = ExerciseType::where('is_active', true)->orderBy('name')->get();

        return view('workout-exercises.index', compact('workoutExercises', 'workoutSessions', 'exerciseTypes'));
    }

    /**
     * Show the form for creating a new workout exercise.
     */
    public function create(Request $request)
    {
        $workoutSessionId = $request->get('workout_session_id');
        $workoutSessions = WorkoutSession::with('user')->orderBy('date', 'desc')->get();
        $exerciseTypes = ExerciseType::where('is_active', true)->orderBy('name')->get();
        
        return view('workout-exercises.create', compact('workoutSessions', 'exerciseTypes', 'workoutSessionId'));
    }

    /**
     * Store a newly created workout exercise.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'workout_session_id' => 'required|exists:workout_sessions,id',
            'exercise_type_id' => 'nullable|exists:exercise_types,id',
            'exercise_name' => 'required|string|max:100',
            'order_in_workout' => 'nullable|integer|min:1',
            'sets' => 'nullable|integer|min:1',
            'reps' => 'nullable|integer|min:1',
            'weight_kg' => 'nullable|numeric|min:0',
            'duration_seconds' => 'nullable|integer|min:1',
            'distance_meters' => 'nullable|numeric|min:0',
            'calories_burned' => 'nullable|integer|min:0',
            'rest_time_seconds' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $workoutExercise = WorkoutExercise::create($validated);

        return redirect()->route('workout-exercises.show', $workoutExercise)
            ->with('success', 'Workout exercise created successfully.');
    }

    /**
     * Display the specified workout exercise.
     */
    public function show(WorkoutExercise $workoutExercise)
    {
        $workoutExercise->load(['workoutSession.user', 'exerciseType']);
        return view('workout-exercises.show', compact('workoutExercise'));
    }

    /**
     * Show the form for editing the workout exercise.
     */
    public function edit(WorkoutExercise $workoutExercise)
    {
        $workoutSessions = WorkoutSession::with('user')->orderBy('date', 'desc')->get();
        $exerciseTypes = ExerciseType::where('is_active', true)->orderBy('name')->get();
        
        return view('workout-exercises.edit', compact('workoutExercise', 'workoutSessions', 'exerciseTypes'));
    }

    /**
     * Update the specified workout exercise.
     */
    public function update(Request $request, WorkoutExercise $workoutExercise)
    {
        $validated = $request->validate([
            'workout_session_id' => 'required|exists:workout_sessions,id',
            'exercise_type_id' => 'nullable|exists:exercise_types,id',
            'exercise_name' => 'required|string|max:100',
            'order_in_workout' => 'nullable|integer|min:1',
            'sets' => 'nullable|integer|min:1',
            'reps' => 'nullable|integer|min:1',
            'weight_kg' => 'nullable|numeric|min:0',
            'duration_seconds' => 'nullable|integer|min:1',
            'distance_meters' => 'nullable|numeric|min:0',
            'calories_burned' => 'nullable|integer|min:0',
            'rest_time_seconds' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $workoutExercise->update($validated);

        return redirect()->route('workout-exercises.show', $workoutExercise)
            ->with('success', 'Workout exercise updated successfully.');
    }

    /**
     * Remove the specified workout exercise.
     */
    public function destroy(WorkoutExercise $workoutExercise)
    {
        $workoutExercise->delete();

        return redirect()->route('workout-exercises.index')
            ->with('success', 'Workout exercise deleted successfully.');
    }

    /**
     * Show workout exercise statistics.
     */
    public function statistics(Request $request)
    {
        $query = WorkoutExercise::query();

        // Filter by exercise type if provided
        if ($request->has('exercise_type_id') && $request->exercise_type_id) {
            $query->where('exercise_type_id', $request->exercise_type_id);
        }

        $stats = [
            'total_exercises' => $query->count(),
            'avg_sets' => $query->avg('sets'),
            'avg_reps' => $query->avg('reps'),
            'avg_weight' => $query->avg('weight_kg'),
            'avg_duration' => $query->avg('duration_seconds'),
            'total_calories' => $query->sum('calories_burned'),
        ];

        $mostPerformedExercises = $query->selectRaw('exercise_name, COUNT(*) as count, AVG(weight_kg) as avg_weight, AVG(reps) as avg_reps')
            ->groupBy('exercise_name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $exerciseTypes = ExerciseType::where('is_active', true)->orderBy('name')->get();

        return view('workout-exercises.statistics', compact('stats', 'mostPerformedExercises', 'exerciseTypes'));
    }
}
