@extends('layouts.app')

@section('title', 'Nutrition Logs - Fitness Tracker')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">🍎 Nutrition Logs</h1>
        <p class="page-subtitle">Track your daily nutrition intake</p>
    </div>
    <div>
        <a href="{{ route('nutrition-logs.create') }}" class="btn-primary">➕ Log Food</a>
        <a href="{{ route('nutrition-logs.daily-summary') }}" class="btn-secondary">📊 Daily Summary</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="{{ route('nutrition-logs.index') }}" class="flex items-center gap-4">
        <select name="user_id" class="filter-select">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->first_name }} {{ $user->last_name }}
                </option>
            @endforeach
        </select>

        <select name="meal_type" class="filter-select">
            <option value="">All Meals</option>
            <option value="breakfast" {{ request('meal_type') == 'breakfast' ? 'selected' : '' }}>Breakfast</option>
            <option value="lunch" {{ request('meal_type') == 'lunch' ? 'selected' : '' }}>Lunch</option>
            <option value="dinner" {{ request('meal_type') == 'dinner' ? 'selected' : '' }}>Dinner</option>
            <option value="snack" {{ request('meal_type') == 'snack' ? 'selected' : '' }}>Snack</option>
            <option value="pre_workout" {{ request('meal_type') == 'pre_workout' ? 'selected' : '' }}>Pre-Workout</option>
            <option value="post_workout" {{ request('meal_type') == 'post_workout' ? 'selected' : '' }}>Post-Workout</option>
        </select>

        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input" placeholder="Start Date">
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input" placeholder="End Date">

        <button type="submit" class="btn-primary">🔍 Filter</button>
        <a href="{{ route('nutrition-logs.index') }}" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Nutrition Logs Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Meal</th>
                <th>Food</th>
                <th>Quantity</th>
                <th>Calories</th>
                <th>Protein</th>
                <th>Carbs</th>
                <th>Fat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nutritionLogs as $log)
                <tr>
                    <td>{{ $log->date->format('M d, Y') }}</td>
                    <td>{{ $log->user->first_name }} {{ $log->user->last_name }}</td>
                    <td>
                        @if($log->meal_type)
                            <span class="meal-badge">{{ ucfirst($log->meal_type) }}</span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td><strong>{{ $log->food_name }}</strong></td>
                    <td>{{ $log->quantity }} {{ $log->unit }}</td>
                    <td>
                        @if($log->calories)
                            <span class="calorie-text">{{ number_format($log->calories) }}</span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>{{ $log->protein_g ? number_format($log->protein_g, 1) . 'g' : 'N/A' }}</td>
                    <td>{{ $log->carbs_g ? number_format($log->carbs_g, 1) . 'g' : 'N/A' }}</td>
                    <td>{{ $log->fat_g ? number_format($log->fat_g, 1) . 'g' : 'N/A' }}</td>
                    <td>
                        <div class="flex gap-4">
                            <a href="{{ route('nutrition-logs.show', $log) }}" class="btn-secondary">👁️ View</a>
                            <a href="{{ route('nutrition-logs.edit', $log) }}" class="btn-primary">✏️ Edit</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        No nutrition logs found. <a href="{{ route('nutrition-logs.create') }}">Log your first meal</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($nutritionLogs->hasPages())
    <div class="pagination">
        {{ $nutritionLogs->links() }}
    </div>
@endif

<style>
.meal-badge {
    display: inline-block;
    padding: 4px 8px;
    background-color: #38a169;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.calorie-text {
    font-weight: bold;
    color: #e53e3e;
}
</style>
@endsection
