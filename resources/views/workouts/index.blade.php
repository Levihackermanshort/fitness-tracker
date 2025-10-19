@extends('layouts.app')

@section('title', 'Workout Dashboard - Fitness Tracker')

@section('description', 'View and manage all your workouts. Track your fitness journey with detailed stats and analytics.')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">🏋️ Workout Dashboard</h1>
            <p class="page-subtitle">Track your fitness journey and stay motivated!</p>
        </div>
        <a href="{{ route('workouts.create') }}" class="btn-primary">
            ➕ Log New Workout
        </a>
    </div>

    <!-- Statistics Dashboard -->
    @if($stats['total_workouts'] > 0)
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_workouts'] }}</div>
                <div class="stat-label">Total Workouts</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_duration'] }}</div>
                <div class="stat-label">Minutes Exercised</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_calories'] ?? 0 }}</div>
                <div class="stat-label">Calories Burned</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['average_duration'] }}</div>
                <div class="stat-label">Avg Duration (min)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ str_replace(' ', '<br>', $stats['popular_exercise']) }}</div>
                <div class="stat-label">Favorite Exercise</div>
            </div>
        </div>
    @endif

    <!-- Search and Filter Bar -->
    <div class="search-filter-bar">
        <form method="GET" action="{{ route('workouts.index') }}" style="display: flex; align-items: center; flex-wrap: wrap; gap: 15px;">
            <!-- Search Input -->
            <input type="text" 
                   name="search" 
                   class="search-input" 
                   placeholder="🔍 Search by exercise, notes, or date..." 
                   value="{{ request('search') }}"
                   style="min-width: 300px;">

            <!-- Date Filter -->
            <select name="date_filter" class="filter-select">
                <option value="">All Time</option>
                <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>Last 7 Days</option>
                <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>Last 30 Days</option>
                <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom Range</option>
            </select>

            <!-- Minimum Duration Filter -->
            <select name="min_duration" class="filter-select">
                <option value="">Any Duration</option>
                <option value="30" {{ request('min_duration') == '30' ? 'selected' : '' }}>30+ minutes</option>
                <option value="60" {{ request('min_duration') == '60' ? 'selected' : '' }}>1+ hours</option>
                <option value="120" {{ request('min_duration') == '120' ? 'selected' : '' }}>2+ hours</option>
            </select>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary">
                🔍 Filter
            </button>

            <!-- Clear Filters -->
            <a href="{{ route('workouts.index') }}" class="btn-secondary">
                🗑️ Clear
            </a>
        </form>

        <!-- Custom Date Range (shown when custom is selected) -->
        @if(request('date_filter') == 'custom')
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                <label for="start_date" class="form-label">Custom Date Range:</label>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <input type="date" name="start_date" id="start_date" class="form-input" 
                           value="{{ request('start_date') }}" style="width: auto;">
                    <span>to</span>
                    <input type="date" name="end_date" id="end_date" class="form-input" 
                           value="{{ request('end_date') }}" style="width: auto;">
                </div>
            </div>
        @endif
    </div>

    <!-- Workouts Table -->
    <div class="table-container">
        @if($workouts->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>📅 Date</th>
                        <th>💪 Exercise</th>
                        <th>⏱️ Duration</th>
                        <th>🔥 Calories</th>
                        <th>📝 Efficiency</th>
                        <th>📄 Notes</th>
                        <th>⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workouts as $workout)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $workout->formatted_date }}</div>
                                <div style="font-size: 0.875rem; color: #718096;">
                                    {{ $workout->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #667eea;">{{ $workout->exercise }}</div>
                                @if($workout->calories_per_minute)
                                    <div style="font-size: 0.875rem; color: #718096;">
                                        {{ $workout->calories_per_minute }} cal/min
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $workout->formatted_duration }}</div>
                                <div style="font-size: 0.875rem; color: #718096;">
                                    ({{ $workout->duration }} min)
                                </div>
                            </td>
                            <td>
                                @if($workout->calories)
                                    <div style="font-weight: 600; color: #e53e3e;">{{ number_format($workout->calories) }}</div>
                                @else
                                    <span style="color: #a0aec0;">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($workout->calories && $workout->duration > 0)
                                    @php
                                        $efficiency = $workout->calories_per_minute;
                                        if($efficiency >= 8) $level = '🔥 Excellent';
                                        elseif($efficiency >= 5) $level = '⭐ Good';
                                        elseif($efficiency >= 3) $level = '👍 Moderate';
                                        else $level = '🌱 Light';
                                    @endphp
                                    <span style="font-size: 0.875rem;">{{ $level }}</span>
                                @else
                                    <span style="color: #a0aec0;">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($workout->notes)
                                    <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $workout->notes }}
                                    </div>
                                @else
                                    <span style="color: #a0aec0;">No notes</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('workouts.show', $workout) }}" 
                                       class="btn-secondary" 
                                       style="padding: 6px 12px; font-size: 0.875rem;">
                                        👁️ View
                                    </a>
                                    <a href="{{ route('workouts.edit', $workout) }}" 
                                       class="btn-primary" 
                                       style="padding: 6px 12px; font-size: 0.875rem;">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('workouts.destroy', $workout) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Are you sure you want to delete this workout?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn-danger" 
                                                style="padding: 6px 12px; font-size: 0.875rem;">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <!-- Empty State -->
            <div style="text-align: center; padding: 60px 20px; color: #718096;">
                <div style="font-size: 4rem; margin-bottom: 20px;">🏋️‍♀️</div>
                <h3 style="color: #4a5568; margin-bottom: 15px;">
                    @if(request()->hasAny(['search', 'date_filter', 'min_duration']))
                        No workouts found matching your filters
                    @else
                        No workouts logged yet
                    @endif
                </h3>
                <p style="margin-bottom: 30px;">
                    @if(request()->hasAny(['search', 'date_filter', 'min_duration']))
                        Try adjusting your search criteria or <a href="{{ route('workouts.index') }}">clear all filters</a>.
                    @else
                        Start your fitness journey by logging your first workout!
                    @endif
                </p>
                @if(!request()->hasAny(['search', 'date_filter', 'min_duration']))
                    <a href="{{ route('workouts.create') }}" class="btn-primary">
                        ➕ Log Your First Workout
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($workouts->hasPages())
        <div class="pagination">
            {{ $workouts->links() }}
        </div>
    @endif

    <!-- Quick Actions -->
    @if($workouts->count() > 0)
        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <h3 style="color: #4a5568; margin-bottom: 20px;">Quick Actions</h3>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('workouts.create') }}" class="btn-primary">
                    ➕ Add Another Workout
                </a>
                <a href="{{ route('workouts.export') }}" class="btn-secondary">
                    📥 Export All Data
                </a>
                <a href="{{ route('workouts.index', ['date_filter' => 'today']) }}" class="btn-secondary">
                    📅 View Today's Workouts
                </a>
                <a href="{{ route('workouts.index', ['date_filter' => 'week']) }}" class="btn-secondary">
                    📊 This Week's Activity
                </a>
            </div>
        </div>
    @endif
@endsection
