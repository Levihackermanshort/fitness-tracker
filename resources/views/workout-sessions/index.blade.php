@extends('layouts.app')

@section('title', 'Workout Sessions - Fitness Tracker')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">🏃 Workout Sessions</h1>
        <p class="page-subtitle">Track your detailed workout sessions</p>
    </div>
    <div>
        <a href="{{ route('workout-sessions.create') }}" class="btn-primary">➕ Add Workout</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="{{ route('workout-sessions.index') }}" class="flex items-center gap-4">
        <select name="user_id" class="filter-select">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->first_name }} {{ $user->last_name }}
                </option>
            @endforeach
        </select>

        <select name="workout_type" class="filter-select">
            <option value="">All Types</option>
            <option value="strength" {{ request('workout_type') == 'strength' ? 'selected' : '' }}>Strength</option>
            <option value="cardio" {{ request('workout_type') == 'cardio' ? 'selected' : '' }}>Cardio</option>
            <option value="flexibility" {{ request('workout_type') == 'flexibility' ? 'selected' : '' }}>Flexibility</option>
            <option value="mixed" {{ request('workout_type') == 'mixed' ? 'selected' : '' }}>Mixed</option>
            <option value="sports" {{ request('workout_type') == 'sports' ? 'selected' : '' }}>Sports</option>
            <option value="outdoor" {{ request('workout_type') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
        </select>

        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input" placeholder="Start Date">
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input" placeholder="End Date">

        <button type="submit" class="btn-primary">🔍 Filter</button>
        <a href="{{ route('workout-sessions.index') }}" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Workout Sessions Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Workout Name</th>
                <th>Type</th>
                <th>Duration</th>
                <th>Calories</th>
                <th>Mood</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($workoutSessions as $session)
                <tr>
                    <td>{{ $session->date->format('M d, Y') }}</td>
                    <td>{{ $session->user->first_name }} {{ $session->user->last_name }}</td>
                    <td><strong>{{ $session->name }}</strong></td>
                    <td>
                        @if($session->workout_type)
                            <span class="badge">{{ ucfirst($session->workout_type) }}</span>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $session->total_duration ? $session->total_duration . ' min' : 'N/A' }}</td>
                    <td>{{ $session->total_calories ? number_format($session->total_calories) : 'N/A' }}</td>
                    <td>
                        @if($session->mood_before && $session->mood_after)
                            {{ $session->mood_before }} → {{ $session->mood_after }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($session->is_completed)
                            <span class="text-success">✅ Completed</span>
                        @else
                            <span class="text-muted">⏳ Incomplete</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-4">
                            <a href="{{ route('workout-sessions.show', $session) }}" class="btn-secondary">👁️ View</a>
                            <a href="{{ route('workout-sessions.edit', $session) }}" class="btn-primary">✏️ Edit</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        No workout sessions found. <a href="{{ route('workout-sessions.create') }}">Create the first workout</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($workoutSessions->hasPages())
    <div class="pagination">
        {{ $workoutSessions->links() }}
    </div>
@endif
@endsection
