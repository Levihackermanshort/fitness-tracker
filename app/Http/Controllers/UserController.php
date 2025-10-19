<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by activity level if provided
        if ($request->has('activity_level') && $request->activity_level) {
            $query->where('activity_level', $request->activity_level);
        }

        // Filter by fitness goal if provided
        if ($request->has('fitness_goal') && $request->fitness_goal) {
            $query->where('fitness_goal', $request->fitness_goal);
        }

        // Search by name or username
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|email|max:100|unique:users',
            'password_hash' => 'required|string|min:8',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'height_cm' => 'nullable|integer|min:100|max:250',
            'weight_kg' => 'nullable|numeric|min:20|max:500',
            'activity_level' => 'nullable|in:sedentary,lightly_active,moderately_active,very_active,extremely_active',
            'fitness_goal' => 'nullable|in:weight_loss,muscle_gain,endurance,strength,general_fitness,maintenance',
            'profile_picture_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);

        $user = User::create($validated);

        return redirect()->route('users.show', $user)
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['workoutSessions', 'goals', 'nutritionLogs', 'bodyMeasurements']);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'password_hash' => 'nullable|string|min:8',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'height_cm' => 'nullable|integer|min:100|max:250',
            'weight_kg' => 'nullable|numeric|min:20|max:500',
            'activity_level' => 'nullable|in:sedentary,lightly_active,moderately_active,very_active,extremely_active',
            'fitness_goal' => 'nullable|in:weight_loss,muscle_gain,endurance,strength,general_fitness,maintenance',
            'profile_picture_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'email_verified_at' => 'nullable|date',
        ]);

        // Only update password if provided
        if (empty($validated['password_hash'])) {
            unset($validated['password_hash']);
        }

        $user->update($validated);

        return redirect()->route('users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Show user dashboard.
     */
    public function dashboard(User $user)
    {
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

        return view('users.dashboard', compact('user', 'stats', 'recentWorkouts', 'activeGoals', 'recentNutritionLogs', 'latestMeasurements'));
    }
}