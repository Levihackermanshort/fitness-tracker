@extends('layouts.app')

@section('title', 'Workout Exercises - Fitness Tracker')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">🏋️ Workout Exercises</h1>
        <p class="page-subtitle">Track individual exercises in workout sessions</p>
    </div>
    <div>
        <a href="{{ route('workout-exercises.create') }}" class="btn-primary">➕ Add Exercise</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="{{ route('workout-exercises.index') }}" class="flex items-center gap-4">
        <select name="workout_session_id" class="filter-select">
            <option value="">All Workout Sessions</option>
            @foreach($workoutSessions as $session)
                <option value="{{ $session->id }}" {{ request('workout_session_id') == $session->id ? 'selected' : '' }}>
                    {{ $session->date->format('M d, Y') }} - {{ $session->user->first_name }} {{ $session->user->last_name }}
                </option>
            @endforeach
        </select>

        <select name="exercise_type_id" class="filter-select">
            <option value="">All Exercise Types</option>
            @foreach($exerciseTypes as $exercise)
                <option value="{{ $exercise->id }}" {{ request('exercise_type_id') == $exercise->id ? 'selected' : '' }}>
                    {{ $exercise->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-primary">🔍 Filter</button>
        <a href="{{ route('workout-exercises.index') }}" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Workout Exercises Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Workout</th>
                <th>Exercise</th>
                <th>Sets</th>
                <th>Reps</th>
                <th>Weight</th>
                <th>Duration</th>
                <th>Calories</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($workoutExercises as $exercise)
                <tr>
                    <td>{{ $exercise->workoutSession->date->format('M d, Y') }}</td>
                    <td>{{ $exercise->workoutSession->user->first_name }} {{ $exercise->workoutSession->user->last_name }}</td>
                    <td><strong>{{ $exercise->workoutSession->name }}</strong></td>
                    <td>
                        <strong>{{ $exercise->exercise_name }}</strong>
                        @if($exercise->exerciseType)
                            <br><small class="text-muted">{{ $exercise->exerciseType->category }}</small>
                        @endif
                    </td>
                    <td>
                        @if($exercise->sets)
                            {{ $exercise->sets }}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($exercise->reps)
                            {{ $exercise->reps }}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($exercise->weight_kg)
                            {{ number_format($exercise->weight_kg, 1) }} kg
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($exercise->duration_seconds)
                            {{ number_format($exercise->duration_seconds / 60, 1) }} min
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($exercise->calories_burned)
                            <span class="calorie-text">{{ number_format($exercise->calories_burned) }}</span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-4">
                            <a href="{{ route('workout-exercises.show', $exercise) }}" class="btn-secondary">👁️ View</a>
                            <a href="{{ route('workout-exercises.edit', $exercise) }}" class="btn-primary">✏️ Edit</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        No workout exercises found. <a href="{{ route('workout-exercises.create') }}">Add your first exercise</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($workoutExercises->hasPages())
    <div class="pagination">
        {{ $workoutExercises->links() }}
    </div>
@endif

<style>
.calorie-text {
    font-weight: bold;
    color: #e53e3e;
}
</style>
@endsection
