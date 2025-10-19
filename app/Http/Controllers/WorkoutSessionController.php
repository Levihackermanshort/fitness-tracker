<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSession;
use App\Models\User;
use Illuminate\Http\Request;

class WorkoutSessionController extends Controller
{
    /**
     * Display a listing of workout sessions.
     */
    public function index(Request $request)
    {
        $query = WorkoutSession::with(['user', 'workoutExercises.exerciseType']);

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by workout type if provided
        if ($request->has('workout_type') && $request->workout_type) {
            $query->where('workout_type', $request->workout_type);
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        $workoutSessions = $query->orderBy('date', 'desc')->paginate(10);
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('workout-sessions.index', compact('workoutSessions', 'users'));
    }

    /**
     * Show the form for creating a new workout session.
     */
    public function create()
    {
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();
        return view('workout-sessions.create', compact('users'));
    }

    /**
     * Store a newly created workout session.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'total_duration' => 'nullable|integer|min:1',
            'total_calories' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'mood_before' => 'nullable|integer|min:1|max:5',
            'mood_after' => 'nullable|integer|min:1|max:5',
            'perceived_exertion' => 'nullable|integer|min:1|max:10',
            'weather_conditions' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:100',
            'workout_type' => 'nullable|in:strength,cardio,flexibility,mixed,sports,outdoor',
            'is_completed' => 'boolean',
        ]);

        $workoutSession = WorkoutSession::create($validated);

        return redirect()->route('workout-sessions.show', $workoutSession)
            ->with('success', 'Workout session created successfully.');
    }

    /**
     * Display the specified workout session.
     */
    public function show(WorkoutSession $workoutSession)
    {
        $workoutSession->load(['user', 'workoutExercises.exerciseType']);
        return view('workout-sessions.show', compact('workoutSession'));
    }

    /**
     * Show the form for editing the workout session.
     */
    public function edit(WorkoutSession $workoutSession)
    {
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();
        return view('workout-sessions.edit', compact('workoutSession', 'users'));
    }

    /**
     * Update the specified workout session.
     */
    public function update(Request $request, WorkoutSession $workoutSession)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'total_duration' => 'nullable|integer|min:1',
            'total_calories' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'mood_before' => 'nullable|integer|min:1|max:5',
            'mood_after' => 'nullable|integer|min:1|max:5',
            'perceived_exertion' => 'nullable|integer|min:1|max:10',
            'weather_conditions' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:100',
            'workout_type' => 'nullable|in:strength,cardio,flexibility,mixed,sports,outdoor',
            'is_completed' => 'boolean',
        ]);

        $workoutSession->update($validated);

        return redirect()->route('workout-sessions.show', $workoutSession)
            ->with('success', 'Workout session updated successfully.');
    }

    /**
     * Remove the specified workout session.
     */
    public function destroy(WorkoutSession $workoutSession)
    {
        $workoutSession->delete();

        return redirect()->route('workout-sessions.index')
            ->with('success', 'Workout session deleted successfully.');
    }

    /**
     * Show workout statistics.
     */
    public function statistics(Request $request)
    {
        $query = WorkoutSession::query();

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

        return view('workout-sessions.statistics', compact('stats', 'workoutTypes', 'monthlyStats', 'users'));
    }
}
