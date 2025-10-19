@extends('layouts.app')

@section('title', 'Exercise Types - Fitness Tracker')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">💪 Exercise Types</h1>
        <p class="page-subtitle">Manage exercise types and categories</p>
    </div>
    <div>
        <a href="{{ route('exercise-types.create') }}" class="btn-primary">➕ Add Exercise</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="{{ route('exercise-types.index') }}" class="flex items-center gap-4">
        <input type="text" name="search" placeholder="Search exercises..." value="{{ request('search') }}" class="search-input">
        
        <select name="category" class="filter-select">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                    {{ ucfirst($category) }}
                </option>
            @endforeach
        </select>

        <select name="difficulty_level" class="filter-select">
            <option value="">All Difficulty Levels</option>
            <option value="1" {{ request('difficulty_level') == '1' ? 'selected' : '' }}>⭐ Beginner</option>
            <option value="2" {{ request('difficulty_level') == '2' ? 'selected' : '' }}>⭐⭐ Easy</option>
            <option value="3" {{ request('difficulty_level') == '3' ? 'selected' : '' }}>⭐⭐⭐ Intermediate</option>
            <option value="4" {{ request('difficulty_level') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ Advanced</option>
            <option value="5" {{ request('difficulty_level') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ Expert</option>
        </select>

        <select name="muscle_group" class="filter-select">
            <option value="">All Muscle Groups</option>
            @foreach($muscleGroups as $muscleGroup)
                <option value="{{ $muscleGroup }}" {{ request('muscle_group') == $muscleGroup ? 'selected' : '' }}>
                    {{ ucfirst($muscleGroup) }}
                </option>
            @endforeach
        </select>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="include_inactive" value="1" {{ request('include_inactive') ? 'checked' : '' }}>
            Include Inactive
        </label>

        <button type="submit" class="btn-primary">🔍 Filter</button>
        <a href="{{ route('exercise-types.index') }}" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Exercise Types Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Muscle Groups</th>
                <th>Equipment</th>
                <th>Difficulty</th>
                <th>Calories/min</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exerciseTypes as $exercise)
                <tr>
                    <td><strong>{{ $exercise->name }}</strong></td>
                    <td>
                        <span class="badge">{{ ucfirst($exercise->category) }}</span>
                    </td>
                    <td>
                        @php
                            $muscles = [];
                            if (is_array($exercise->muscle_groups)) {
                                $muscles = $exercise->muscle_groups;
                            } elseif (is_string($exercise->muscle_groups)) {
                                $decoded = json_decode($exercise->muscle_groups, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $muscles = $decoded;
                                } else {
                                    $muscles = array_filter(array_map('trim', explode(',', $exercise->muscle_groups)));
                                }
                            }
                        @endphp
                        @if(count($muscles) > 0)
                            @foreach($muscles as $muscle)
                                <span class="muscle-tag">{{ ucfirst($muscle) }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>{{ $exercise->equipment_needed ?? 'None' }}</td>
                    <td>
                        @if($exercise->difficulty_level)
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $exercise->difficulty_level)
                                    ⭐
                                @else
                                    ☆
                                @endif
                            @endfor
                            ({{ $exercise->difficulty_level }}/5)
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $exercise->calories_per_minute ? number_format($exercise->calories_per_minute, 1) : 'N/A' }}</td>
                    <td>
                        @if($exercise->is_active)
                            <span class="text-success">✅ Active</span>
                        @else
                            <span class="text-muted">❌ Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-4">
                            <a href="{{ route('exercise-types.show', $exercise) }}" class="btn-secondary">👁️ View</a>
                            <a href="{{ route('exercise-types.edit', $exercise) }}" class="btn-primary">✏️ Edit</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No exercise types found. <a href="{{ route('exercise-types.create') }}">Add the first exercise</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($exerciseTypes->hasPages())
    <div class="pagination">
        {{ $exerciseTypes->links() }}
    </div>
@endif

<style>
.badge {
    display: inline-block;
    padding: 4px 8px;
    background-color: #667eea;
    color: white;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.muscle-tag {
    display: inline-block;
    padding: 2px 6px;
    background-color: #f7fafc;
    color: #4a5568;
    border: 1px solid #e2e8f0;
    border-radius: 3px;
    font-size: 11px;
    margin-right: 4px;
    margin-bottom: 2px;
}
</style>
@endsection
