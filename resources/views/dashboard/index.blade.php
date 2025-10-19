@extends('layouts.app')

@section('title', 'Dashboard - Fitness Tracker')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">📊 Dashboard</h1>
        <p class="page-subtitle">Welcome to your fitness tracking dashboard</p>
    </div>
</div>

<!-- User Selection -->
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">👤 Select User</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-4">
            <select name="user_id" class="form-input" onchange="this.form.submit()">
                <option value="">All Users Overview</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->first_name }} {{ $user->last_name }} ({{ $user->username }})
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

@if(request('user_id'))
    <!-- User-specific Dashboard -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_workouts'] }}</div>
            <div class="stat-label">Total Workouts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['total_calories']) }}</div>
            <div class="stat-label">Calories Burned</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($stats['total_duration']) }}</div>
            <div class="stat-label">Minutes Exercised</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['active_goals'] }}</div>
            <div class="stat-label">Active Goals</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['achieved_goals'] }}</div>
            <div class="stat-label">Achieved Goals</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['latest_weight'] ? number_format($stats['latest_weight'], 1) . ' kg' : 'N/A' }}</div>
            <div class="stat-label">Latest Weight</div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">🏃 Recent Workouts</h3>
        </div>
        <div class="card-body">
            @if($recentWorkouts->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Workout</th>
                                <th>Duration</th>
                                <th>Calories</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentWorkouts as $workout)
                                <tr>
                                    <td>{{ $workout->date->format('M d, Y') }}</td>
                                    <td>{{ $workout->name }}</td>
                                    <td>{{ $workout->total_duration ? $workout->total_duration . ' min' : 'N/A' }}</td>
                                    <td>{{ $workout->total_calories ? number_format($workout->total_calories) : 'N/A' }}</td>
                                    <td>{{ ucfirst($workout->workout_type ?? 'N/A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No recent workouts found.</p>
            @endif
        </div>
    </div>

    <!-- Active Goals -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">🎯 Active Goals</h3>
        </div>
        <div class="card-body">
            @if($activeGoals->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Goal</th>
                                <th>Type</th>
                                <th>Progress</th>
                                <th>Target Date</th>
                                <th>Priority</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeGoals as $goal)
                                <tr>
                                    <td>{{ $goal->title }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $goal->goal_type)) }}</td>
                                    <td>
                                        @if($goal->target_value && $goal->current_value)
                                            {{ number_format(($goal->current_value / $goal->target_value) * 100, 1) }}%
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $goal->target_date ? $goal->target_date->format('M d, Y') : 'No target' }}</td>
                                    <td>{{ $goal->priority ? '⭐' . $goal->priority : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No active goals found.</p>
            @endif
        </div>
    </div>

    <!-- Recent Nutrition Logs -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">🍎 Recent Nutrition Logs</h3>
        </div>
        <div class="card-body">
            @if($recentNutritionLogs->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Meal</th>
                                <th>Food</th>
                                <th>Calories</th>
                                <th>Protein</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentNutritionLogs as $log)
                                <tr>
                                    <td>{{ $log->date->format('M d, Y') }}</td>
                                    <td>{{ ucfirst($log->meal_type ?? 'N/A') }}</td>
                                    <td>{{ $log->food_name }}</td>
                                    <td>{{ $log->calories ? number_format($log->calories) : 'N/A' }}</td>
                                    <td>{{ $log->protein_g ? number_format($log->protein_g, 1) . 'g' : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No recent nutrition logs found.</p>
            @endif
        </div>
    </div>

    <!-- Latest Body Measurements -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">📏 Latest Body Measurements</h3>
        </div>
        <div class="card-body">
            @if($latestMeasurements->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Weight</th>
                                <th>Body Fat %</th>
                                <th>Muscle Mass</th>
                                <th>BMI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestMeasurements as $measurement)
                                <tr>
                                    <td>{{ $measurement->measurement_date->format('M d, Y') }}</td>
                                    <td>{{ $measurement->weight_kg ? number_format($measurement->weight_kg, 1) . ' kg' : 'N/A' }}</td>
                                    <td>{{ $measurement->body_fat_percentage ? number_format($measurement->body_fat_percentage, 1) . '%' : 'N/A' }}</td>
                                    <td>{{ $measurement->muscle_mass_kg ? number_format($measurement->muscle_mass_kg, 1) . ' kg' : 'N/A' }}</td>
                                    <td>{{ $measurement->bmi ? number_format($measurement->bmi, 1) : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No body measurements found.</p>
            @endif
        </div>
    </div>

@else
    <!-- General Dashboard -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_users'] }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_workouts'] }}</div>
            <div class="stat-label">Total Workouts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_goals'] }}</div>
            <div class="stat-label">Total Goals</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_nutrition_logs'] }}</div>
            <div class="stat-label">Nutrition Logs</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_body_measurements'] }}</div>
            <div class="stat-label">Body Measurements</div>
        </div>
    </div>

    <!-- Recent Workouts -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">🏃 Recent Workouts</h3>
        </div>
        <div class="card-body">
            @if($recentWorkouts->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Workout</th>
                                <th>Duration</th>
                                <th>Calories</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentWorkouts as $workout)
                                <tr>
                                    <td>{{ $workout->date->format('M d, Y') }}</td>
                                    <td>{{ $workout->user->first_name }} {{ $workout->user->last_name }}</td>
                                    <td>{{ $workout->name }}</td>
                                    <td>{{ $workout->total_duration ? $workout->total_duration . ' min' : 'N/A' }}</td>
                                    <td>{{ $workout->total_calories ? number_format($workout->total_calories) : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No recent workouts found.</p>
            @endif
        </div>
    </div>

    <!-- Recent Goals -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">🎯 Recent Goals</h3>
        </div>
        <div class="card-body">
            @if($recentGoals->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Goal</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Priority</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentGoals as $goal)
                                <tr>
                                    <td>{{ $goal->user->first_name }} {{ $goal->user->last_name }}</td>
                                    <td>{{ $goal->title }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $goal->goal_type)) }}</td>
                                    <td>
                                        @if($goal->is_achieved)
                                            <span class="text-success">✅ Achieved</span>
                                        @else
                                            <span class="text-muted">🔄 Active</span>
                                        @endif
                                    </td>
                                    <td>{{ $goal->priority ? '⭐' . $goal->priority : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No recent goals found.</p>
            @endif
        </div>
    </div>
@endif

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">⚡ Quick Actions</h3>
    </div>
    <div class="card-body">
        <div class="flex gap-4">
            <a href="{{ route('workout-sessions.create') }}" class="btn-primary">➕ Add Workout</a>
            <a href="{{ route('goals.create') }}" class="btn-primary">🎯 Set Goal</a>
            <a href="{{ route('nutrition-logs.create') }}" class="btn-primary">🍎 Log Food</a>
            <a href="{{ route('body-measurements.create') }}" class="btn-primary">📏 Record Measurement</a>
            <a href="{{ route('workout-plans.index') }}" class="btn-secondary">📋 View Plans</a>
        </div>
    </div>
</div>
@endsection
