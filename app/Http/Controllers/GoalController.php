<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    /**
     * Display a listing of goals.
     */
    public function index(Request $request)
    {
        $query = Goal::with('user');

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by goal type if provided
        if ($request->has('goal_type') && $request->goal_type) {
            $query->where('goal_type', $request->goal_type);
        }

        // Filter by status
        if ($request->has('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true)->where('is_achieved', false);
                    break;
                case 'achieved':
                    $query->where('is_achieved', true);
                    break;
                case 'overdue':
                    $query->where('is_active', true)
                        ->where('is_achieved', false)
                        ->where('target_date', '<', now()->toDateString());
                    break;
            }
        }

        $goals = $query->orderBy('created_at', 'desc')->paginate(10);
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('goals.index', compact('goals', 'users'));
    }

    /**
     * Show the form for creating a new goal.
     */
    public function create()
    {
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();
        return view('goals.create', compact('users'));
    }

    /**
     * Store a newly created goal.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'goal_type' => 'required|in:weight,strength,endurance,flexibility,body_fat,muscle_mass,distance,time,frequency',
            'target_value' => 'nullable|numeric',
            'current_value' => 'nullable|numeric',
            'unit' => 'nullable|string|max:20',
            'start_date' => 'required|date',
            'target_date' => 'nullable|date|after:start_date',
            'priority' => 'nullable|integer|min:1|max:5',
            'is_active' => 'boolean',
        ]);

        $goal = Goal::create($validated);

        return redirect()->route('goals.show', $goal)
            ->with('success', 'Goal created successfully.');
    }

    /**
     * Display the specified goal.
     */
    public function show(Goal $goal)
    {
        $goal->load('user');
        return view('goals.show', compact('goal'));
    }

    /**
     * Show the form for editing the goal.
     */
    public function edit(Goal $goal)
    {
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();
        return view('goals.edit', compact('goal', 'users'));
    }

    /**
     * Update the specified goal.
     */
    public function update(Request $request, Goal $goal)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'goal_type' => 'required|in:weight,strength,endurance,flexibility,body_fat,muscle_mass,distance,time,frequency',
            'target_value' => 'nullable|numeric',
            'current_value' => 'nullable|numeric',
            'unit' => 'nullable|string|max:20',
            'start_date' => 'required|date',
            'target_date' => 'nullable|date|after:start_date',
            'priority' => 'nullable|integer|min:1|max:5',
            'is_active' => 'boolean',
            'is_achieved' => 'boolean',
            'achieved_date' => 'nullable|date',
        ]);

        $goal->update($validated);

        return redirect()->route('goals.show', $goal)
            ->with('success', 'Goal updated successfully.');
    }

    /**
     * Remove the specified goal.
     */
    public function destroy(Goal $goal)
    {
        $goal->delete();

        return redirect()->route('goals.index')
            ->with('success', 'Goal deleted successfully.');
    }

    /**
     * Update goal progress.
     */
    public function updateProgress(Request $request, Goal $goal)
    {
        $validated = $request->validate([
            'current_value' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $goal->update([
            'current_value' => $validated['current_value'],
            'is_achieved' => $validated['current_value'] >= $goal->target_value,
            'achieved_date' => $validated['current_value'] >= $goal->target_value ? now() : null,
        ]);

        return redirect()->route('goals.show', $goal)
            ->with('success', 'Goal progress updated successfully.');
    }

    /**
     * Show goal statistics.
     */
    public function statistics(Request $request)
    {
        $query = Goal::query();

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
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

        return view('goals.statistics', compact('stats', 'goalTypes', 'priorityDistribution', 'users'));
    }
}
