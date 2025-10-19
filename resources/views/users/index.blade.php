@extends('layouts.app')

@section('title', 'Users - Fitness Tracker')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">👥 Users</h1>
        <p class="page-subtitle">Manage fitness tracker users</p>
    </div>
    <div>
        <a href="{{ route('users.create') }}" class="btn-primary">➕ Add User</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-4">
        <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}" class="search-input">
        
        <select name="activity_level" class="filter-select">
            <option value="">All Activity Levels</option>
            <option value="sedentary" {{ request('activity_level') == 'sedentary' ? 'selected' : '' }}>Sedentary</option>
            <option value="lightly_active" {{ request('activity_level') == 'lightly_active' ? 'selected' : '' }}>Lightly Active</option>
            <option value="moderately_active" {{ request('activity_level') == 'moderately_active' ? 'selected' : '' }}>Moderately Active</option>
            <option value="very_active" {{ request('activity_level') == 'very_active' ? 'selected' : '' }}>Very Active</option>
            <option value="extremely_active" {{ request('activity_level') == 'extremely_active' ? 'selected' : '' }}>Extremely Active</option>
        </select>

        <select name="fitness_goal" class="filter-select">
            <option value="">All Fitness Goals</option>
            <option value="weight_loss" {{ request('fitness_goal') == 'weight_loss' ? 'selected' : '' }}>Weight Loss</option>
            <option value="muscle_gain" {{ request('fitness_goal') == 'muscle_gain' ? 'selected' : '' }}>Muscle Gain</option>
            <option value="endurance" {{ request('fitness_goal') == 'endurance' ? 'selected' : '' }}>Endurance</option>
            <option value="strength" {{ request('fitness_goal') == 'strength' ? 'selected' : '' }}>Strength</option>
            <option value="general_fitness" {{ request('fitness_goal') == 'general_fitness' ? 'selected' : '' }}>General Fitness</option>
            <option value="maintenance" {{ request('fitness_goal') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
        </select>

        <button type="submit" class="btn-primary">🔍 Search</button>
        <a href="{{ route('users.index') }}" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Users Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Activity Level</th>
                <th>Fitness Goal</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>
                    </td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->gender ?? 'N/A') }}</td>
                    <td>
                        @if($user->date_of_birth)
                            {{ \Carbon\Carbon::parse($user->date_of_birth)->age }} years
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($user->activity_level)
                            <span class="badge">{{ ucfirst(str_replace('_', ' ', $user->activity_level)) }}</span>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($user->fitness_goal)
                            <span class="badge">{{ ucfirst(str_replace('_', ' ', $user->fitness_goal)) }}</span>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="text-success">✅ Active</span>
                        @else
                            <span class="text-muted">❌ Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-4">
                            <a href="{{ route('users.show', $user) }}" class="btn-secondary">👁️ View</a>
                            <a href="{{ route('users.edit', $user) }}" class="btn-primary">✏️ Edit</a>
                            <a href="{{ route('users.dashboard', $user) }}" class="btn-success">📊 Dashboard</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        No users found. <a href="{{ route('users.create') }}">Create the first user</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($users->hasPages())
    <div class="pagination">
        {{ $users->links() }}
    </div>
@endif
@endsection
