@extends('layouts.app')

@section('title', 'Goals - Fitness Tracker')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">🎯 Goals</h1>
        <p class="page-subtitle">Track your fitness goals and progress</p>
    </div>
    <div>
        <a href="{{ route('goals.create') }}" class="btn-primary">➕ Set Goal</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="{{ route('goals.index') }}" class="flex items-center gap-4">
        <select name="user_id" class="filter-select">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->first_name }} {{ $user->last_name }}
                </option>
            @endforeach
        </select>

        <select name="goal_type" class="filter-select">
            <option value="">All Types</option>
            <option value="weight" {{ request('goal_type') == 'weight' ? 'selected' : '' }}>Weight</option>
            <option value="strength" {{ request('goal_type') == 'strength' ? 'selected' : '' }}>Strength</option>
            <option value="endurance" {{ request('goal_type') == 'endurance' ? 'selected' : '' }}>Endurance</option>
            <option value="flexibility" {{ request('goal_type') == 'flexibility' ? 'selected' : '' }}>Flexibility</option>
            <option value="body_fat" {{ request('goal_type') == 'body_fat' ? 'selected' : '' }}>Body Fat</option>
            <option value="muscle_mass" {{ request('goal_type') == 'muscle_mass' ? 'selected' : '' }}>Muscle Mass</option>
            <option value="distance" {{ request('goal_type') == 'distance' ? 'selected' : '' }}>Distance</option>
            <option value="time" {{ request('goal_type') == 'time' ? 'selected' : '' }}>Time</option>
            <option value="frequency" {{ request('goal_type') == 'frequency' ? 'selected' : '' }}>Frequency</option>
        </select>

        <select name="status" class="filter-select">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="achieved" {{ request('status') == 'achieved' ? 'selected' : '' }}>Achieved</option>
            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
        </select>

        <button type="submit" class="btn-primary">🔍 Filter</button>
        <a href="{{ route('goals.index') }}" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Goals Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Goal</th>
                <th>Type</th>
                <th>Progress</th>
                <th>Target Date</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($goals as $goal)
                <tr>
                    <td>{{ $goal->user->first_name }} {{ $goal->user->last_name }}</td>
                    <td><strong>{{ $goal->title }}</strong></td>
                    <td>
                        <span class="badge">{{ ucfirst(str_replace('_', ' ', $goal->goal_type)) }}</span>
                    </td>
                    <td>
                        @if($goal->target_value && $goal->current_value)
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ min(100, ($goal->current_value / $goal->target_value) * 100) }}%"></div>
                                <span class="progress-text">{{ number_format(($goal->current_value / $goal->target_value) * 100, 1) }}%</span>
                            </div>
                            <small class="text-muted">{{ $goal->current_value }} / {{ $goal->target_value }} {{ $goal->unit }}</small>
                        @else
                            <span class="text-muted">No progress data</span>
                        @endif
                    </td>
                    <td>
                        @if($goal->target_date)
                            {{ $goal->target_date->format('M d, Y') }}
                            @if($goal->target_date->isPast() && !$goal->is_achieved)
                                <br><small class="text-danger">⚠️ Overdue</small>
                            @endif
                        @else
                            No target date
                        @endif
                    </td>
                    <td>
                        @if($goal->priority)
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $goal->priority)
                                    ⭐
                                @else
                                    ☆
                                @endif
                            @endfor
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if($goal->is_achieved)
                            <span class="text-success">✅ Achieved</span>
                            @if($goal->achieved_date)
                                <br><small class="text-muted">{{ $goal->achieved_date->format('M d, Y') }}</small>
                            @endif
                        @elseif($goal->is_active)
                            <span class="text-primary">🔄 Active</span>
                        @else
                            <span class="text-muted">⏸️ Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-4">
                            <a href="{{ route('goals.show', $goal) }}" class="btn-secondary">👁️ View</a>
                            <a href="{{ route('goals.edit', $goal) }}" class="btn-primary">✏️ Edit</a>
                            @if(!$goal->is_achieved && $goal->is_active)
                                <a href="{{ route('goals.update-progress', $goal) }}" class="btn-success">📈 Update Progress</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No goals found. <a href="{{ route('goals.create') }}">Set your first goal</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($goals->hasPages())
    <div class="pagination">
        {{ $goals->links() }}
    </div>
@endif

<style>
.progress-bar {
    position: relative;
    width: 100%;
    height: 20px;
    background-color: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 5px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    transition: width 0.3s ease;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 12px;
    font-weight: bold;
    color: #2d3748;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    background-color: #667eea;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}
</style>
@endsection
