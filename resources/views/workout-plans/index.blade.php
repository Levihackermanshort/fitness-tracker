@extends('layouts.app')

@section('title', 'Workout Plans - Fitness Tracker')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">📋 Workout Plans</h1>
        <p class="page-subtitle">Create and manage workout plans</p>
    </div>
    <div>
        <a href="{{ route('workout-plans.create') }}" class="btn-primary">➕ Create Plan</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="{{ route('workout-plans.index') }}" class="flex items-center gap-4">
        <select name="user_id" class="filter-select">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->first_name }} {{ $user->last_name }}
                </option>
            @endforeach
        </select>

        <select name="plan_type" class="filter-select">
            <option value="">All Types</option>
            <option value="strength" {{ request('plan_type') == 'strength' ? 'selected' : '' }}>Strength</option>
            <option value="cardio" {{ request('plan_type') == 'cardio' ? 'selected' : '' }}>Cardio</option>
            <option value="hybrid" {{ request('plan_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
            <option value="bodyweight" {{ request('plan_type') == 'bodyweight' ? 'selected' : '' }}>Bodyweight</option>
            <option value="sport_specific" {{ request('plan_type') == 'sport_specific' ? 'selected' : '' }}>Sport Specific</option>
            <option value="rehabilitation" {{ request('plan_type') == 'rehabilitation' ? 'selected' : '' }}>Rehabilitation</option>
        </select>

        <select name="difficulty_level" class="filter-select">
            <option value="">All Difficulty Levels</option>
            <option value="1" {{ request('difficulty_level') == '1' ? 'selected' : '' }}>⭐ Beginner</option>
            <option value="2" {{ request('difficulty_level') == '2' ? 'selected' : '' }}>⭐⭐ Easy</option>
            <option value="3" {{ request('difficulty_level') == '3' ? 'selected' : '' }}>⭐⭐⭐ Intermediate</option>
            <option value="4" {{ request('difficulty_level') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ Advanced</option>
            <option value="5" {{ request('difficulty_level') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ Expert</option>
        </select>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="show_templates" value="1" {{ request('show_templates') ? 'checked' : '' }}>
            Show Templates Only
        </label>

        <button type="submit" class="btn-primary">🔍 Filter</button>
        <a href="{{ route('workout-plans.index') }}" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Workout Plans Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>User</th>
                <th>Type</th>
                <th>Difficulty</th>
                <th>Duration</th>
                <th>Frequency</th>
                <th>Exercises</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($workoutPlans as $plan)
                <tr>
                    <td><strong>{{ $plan->name }}</strong></td>
                    <td>
                        @if($plan->is_template)
                            <span class="template-badge">📋 Template</span>
                        @else
                            {{ $plan->user->first_name }} {{ $plan->user->last_name }}
                        @endif
                    </td>
                    <td>
                        @if($plan->plan_type)
                            <span class="type-badge">{{ ucfirst(str_replace('_', ' ', $plan->plan_type)) }}</span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($plan->difficulty_level)
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $plan->difficulty_level)
                                    ⭐
                                @else
                                    ☆
                                @endif
                            @endfor
                            ({{ $plan->difficulty_level }}/5)
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($plan->duration_weeks)
                            {{ $plan->duration_weeks }} weeks
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($plan->frequency_per_week)
                            {{ $plan->frequency_per_week }}x/week
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($plan->planExercises->count() > 0)
                            <span class="exercise-count">{{ $plan->planExercises->count() }} exercises</span>
                        @else
                            <span class="text-muted">No exercises</span>
                        @endif
                    </td>
                    <td>
                        @if($plan->is_active)
                            <span class="text-success">✅ Active</span>
                        @else
                            <span class="text-muted">❌ Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-4">
                            <a href="{{ route('workout-plans.show', $plan) }}" class="btn-secondary">👁️ View</a>
                            <a href="{{ route('workout-plans.edit', $plan) }}" class="btn-primary">✏️ Edit</a>
                            @if($plan->is_template)
                                <a href="{{ route('workout-plans.copy-template', $plan) }}" class="btn-success">📋 Copy</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        No workout plans found. <a href="{{ route('workout-plans.create') }}">Create your first plan</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($workoutPlans->hasPages())
    <div class="pagination">
        {{ $workoutPlans->links() }}
    </div>
@endif

<style>
.template-badge {
    display: inline-block;
    padding: 4px 8px;
    background-color: #667eea;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.type-badge {
    display: inline-block;
    padding: 4px 8px;
    background-color: #38a169;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.exercise-count {
    font-weight: 500;
    color: #4a5568;
}
</style>
@endsection
