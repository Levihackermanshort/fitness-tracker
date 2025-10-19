@extends('layouts.app')

@section('title', 'Body Measurements - Fitness Tracker')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">📏 Body Measurements</h1>
        <p class="page-subtitle">Track your body measurements and progress</p>
    </div>
    <div>
        <a href="{{ route('body-measurements.create') }}" class="btn-primary">➕ Record Measurement</a>
        <a href="{{ route('body-measurements.trends') }}" class="btn-secondary">📈 View Trends</a>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="search-filter-bar">
    <form method="GET" action="{{ route('body-measurements.index') }}" class="flex items-center gap-4">
        <select name="user_id" class="filter-select">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->first_name }} {{ $user->last_name }}
                </option>
            @endforeach
        </select>

        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-input" placeholder="Start Date">
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-input" placeholder="End Date">

        <button type="submit" class="btn-primary">🔍 Filter</button>
        <a href="{{ route('body-measurements.index') }}" class="btn-secondary">🔄 Clear</a>
    </form>
</div>

<!-- Body Measurements Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Weight</th>
                <th>Body Fat %</th>
                <th>Muscle Mass</th>
                <th>BMI</th>
                <th>Chest</th>
                <th>Waist</th>
                <th>Hips</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bodyMeasurements as $measurement)
                <tr>
                    <td>{{ $measurement->measurement_date->format('M d, Y') }}</td>
                    <td>{{ $measurement->user->first_name }} {{ $measurement->user->last_name }}</td>
                    <td>
                        @if($measurement->weight_kg)
                            <span class="weight-text">{{ number_format($measurement->weight_kg, 1) }} kg</span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($measurement->body_fat_percentage)
                            <span class="bodyfat-text">{{ number_format($measurement->body_fat_percentage, 1) }}%</span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($measurement->muscle_mass_kg)
                            {{ number_format($measurement->muscle_mass_kg, 1) }} kg
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($measurement->bmi)
                            <span class="bmi-text">{{ number_format($measurement->bmi, 1) }}</span>
                            @if($measurement->bmi < 18.5)
                                <br><small class="text-info">Underweight</small>
                            @elseif($measurement->bmi < 25)
                                <br><small class="text-success">Normal</small>
                            @elseif($measurement->bmi < 30)
                                <br><small class="text-warning">Overweight</small>
                            @else
                                <br><small class="text-danger">Obese</small>
                            @endif
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>{{ $measurement->chest_cm ? number_format($measurement->chest_cm, 1) . ' cm' : 'N/A' }}</td>
                    <td>{{ $measurement->waist_cm ? number_format($measurement->waist_cm, 1) . ' cm' : 'N/A' }}</td>
                    <td>{{ $measurement->hips_cm ? number_format($measurement->hips_cm, 1) . ' cm' : 'N/A' }}</td>
                    <td>
                        <div class="flex gap-4">
                            <a href="{{ route('body-measurements.show', $measurement) }}" class="btn-secondary">👁️ View</a>
                            <a href="{{ route('body-measurements.edit', $measurement) }}" class="btn-primary">✏️ Edit</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted">
                        No body measurements found. <a href="{{ route('body-measurements.create') }}">Record your first measurement</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($bodyMeasurements->hasPages())
    <div class="pagination">
        {{ $bodyMeasurements->links() }}
    </div>
@endif

<style>
.weight-text {
    font-weight: bold;
    color: #2d3748;
}

.bodyfat-text {
    font-weight: bold;
    color: #e53e3e;
}

.bmi-text {
    font-weight: bold;
    color: #667eea;
}

.text-info { color: #3182ce; }
.text-success { color: #38a169; }
.text-warning { color: #d69e2e; }
.text-danger { color: #e53e3e; }
</style>
@endsection
