@extends('layouts.app')

@section('title', $workout->exercise . ' Workout Details - Fitness Tracker')

@section('description', 'View detailed information about your ' . $workout->exercise . ' workout from ' . $workout->formatted_date . '.')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">👁️ {{ $workout->exercise }}</h1>
            <p class="page-subtitle">{{ $workout->formatted_date }} • {{ $workout->formatted_duration }}</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('workouts.edit', $workout) }}" class="btn-primary">
                ✏️ Edit Workout
            </a>
            <a href="{{ route('workouts.index') }}" class="btn-secondary">
                ↪️ Back to Dashboard
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Main Workout Info -->
        <div class="card">
            <h3 style="color: #4a5568; margin-bottom: 20px;">📊 Workout Details</h3>
            
            <div style="display: grid; gap: 20px;">
                <!-- Exercise -->
                <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 15px;">
                    <div style="font-weight: 600; color: #4a5568; margin-bottom: 5px;">💪 Exercise</div>
                    <div style="font-size: 1.25rem; color: #667eea; font-weight: 600;">{{ $workout->exercise }}</div>
                </div>

                <!-- Date -->
                <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 15px;">
                    <div style="font-weight: 600; color: #4a5568; margin-bottom: 5px;">📅 Date</div>
                    <div style="font-size: 1.1rem;">{{ $workout->formatted_date }}</div>
                </div>

                <!-- Duration -->
                <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 15px;">
                    <div style="font-weight: 600; color: #4a5568; margin-bottom: 5px;">⏱️ Duration</div>
                    <div style="font-size: 1.1rem;">{{ $workout->formatted_duration }}</div>
                    <div style="font-size: 0.875rem; color: #718096; margin-top: 3px;">({{ number_format($workout->duration) }} minutes)</div>
                </div>

                <!-- Calories -->
                <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 15px;">
                    <div style="font-weight: 600; color: #4a5568; margin-bottom: 5px;">🔥 Calories Burned</div>
                    @if($workout->calories)
                        <div style="font-size: 1.1rem; color: #e53e3e;">{{ number_format($workout->calories) }} calories</div>
                        @if($workout->duration > 0)
                            <div style="font-size: 0.875rem; color: #718096; margin-top: 3px;">
                                Efficiency: {{ $workout->calories_per_minute }} calories per minute
                            </div>
                        @endif
                    @else
                        <div style="color: #a0aec0;">Not recorded</div>
                    @endif
                </div>

                <!-- Notes -->
                <div>
                    <div style="font-weight: 600; color: #4a5568; margin-bottom: 10px;">📝 Notes</div>
                    @if($workout->notes)
                        <div style="background: #f7fafc; border-radius: 8px; padding: 15px; line-height: 1.6;">
                            {{ $workout->notes }}
                        </div>
                    @else
                        <div style="color: #a0aec0; font-style: italic;">No notes recorded for this workout</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats and Actions -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <!-- Workout Stats -->
            <div class="card">
                <h4 style="color: #4a5568; margin-bottom: 15px;">📈 Workout Analysis</h4>
                
                @if($workout->calories)
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 0.875rem; color: #718096; margin-bottom: 5px;">Calorie Efficiency</div>
                        <div style="font-size: 1.25rem; font-weight: 600;">
                            @if($workout->calories_per_minute >= 8)
                                🔥 Excellent
                            @elseif($workout->calories_per_minute >= 5)
                                ⭐ Good
                            @elseif($workout->calories_per_minute >= 3)
                                👍 Moderate
                            @else
                                🌱 Light
                            @endif
                        </div>
                        <div style="font-size: 0.875rem; color: #718096;">
                            {{ $workout->calories_per_minute }} cal/min
                        </div>
                    </div>
                @endif

                <div style="margin-bottom: 15px;">
                    <div style="font-size: 0.875rem; color: #718096; margin-bottom: 5px;">Workout Intensity</div>
                    <div style="font-size: 1.25rem; font-weight: 600;">
                        @if($workout->duration >= 120)
                            💪 High Intensity
                        @elseif($workout->duration >= 60)
                            🏃‍♀️ Moderate Intensity
                        @else
                            🚶‍♀️ Low Intensity
                        @endif
                    </div>
                    <div style="font-size: 0.875rem; color: #718096;">
                        Based on {{ $workout->formatted_duration }} duration
                    </div>
                </div>

                <!-- Met Minutes -->
                @if($workout->calories)
                    @php
                        $metMinutes = round($workout->calories / 4, 0);
                    @endphp
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 0.875rem; color: #718096; margin-bottom: 5px;">MET Minutes</div>
                        <div style="font-size: 1.25rem; font-weight: 600;">{{ $metMinutes }}</div>
                        <div style="font-size: 0.875rem; color: #718096;">
                            Estimated metabolic equivalent minutes
                        </div>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <h4 style="color: #4a5568; margin-bottom: 15px;">⚡ Quick Actions</h4>
                <div style="display: grid; gap: 10px;">
                    <a href="{{ route('workouts.edit', $workout) }}" class="btn-primary">
                        ✏️ Edit Details
                    </a>
                    <form action="{{ route('workouts.destroy', $workout) }}" 
                          method="POST" 
                          style="display: block;"
                          onsubmit="return confirm('Are you sure you want to delete this \\''{{ $workout->exercise }}\\'' workout? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger" style="width: 100%;">
                            🗑️ Delete Workout
                        </button>
                    </form>
                    <a href="{{ route('workouts.index', ['search' => $workout->exercise]) }}" class="btn-secondary">
                        🔍 Similar Workouts
                    </a>
                    <a href="{{ route('workouts.create') }}" class="btn-secondary">
                        ➕ Log Another
                    </a>
                </div>
            </div>

            <!-- Workout Timeline -->
            <div class="card">
                <h4 style="color: #4a5568; margin-bottom: 15px;">⏰ Timeline</h4>
                <div style="font-size: 0.875rem; color: #718096; margin-bottom: 10px;">
                    Logged {{ $workout->created_at->diffForHumans() }}
                </div>
                @if($workout->updated_at != $workout->created_at)
                    <div style="font-size: 0.875rem; color: #718096;">
                        Updated {{ $workout->updated_at->diffForHumans() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Workouts -->
    @if($relatedWorkouts->count() > 0)
        <div style="background: white; border-radius: 12px; padding: 30px; margin-top: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <h3 style="color: #4a5568; margin-bottom: 20px;">🏃‍♀️ Similar {{ $workout->exercise }} Workouts</h3>
            <p style="color: #718096; margin-bottom: 20px;">Other workouts you've done with similar exercises:</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;">
                @foreach($relatedWorkouts as $related)
                    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px;">
                        <div style="font-weight: 600; color: #667eea; margin-bottom: 8px;">
                            {{ $related->exercise }}
                        </div>
                        <div style="font-size: 0.875rem; color: #718096; margin-bottom: 10px;">
                            📅 {{ $related->formatted_date }} • ⏱️ {{ $related->formatted_duration }}
                            @if($related->calories)
                                • 🔥 {{ number_format($related->calories) }} cal
                            @endif
                        </div>
                        @if($related->notes)
                            <div style="font-size: 0.875rem; color: #4a5568; margin-bottom: 10px; max-height: 40px; overflow: hidden;">
                                {{ Str::limit($related->notes, 100) }}
                            </div>
                        @endif
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('workouts.show', $related) }}" class="btn-secondary" style="padding: 5px 10px; font-size: 0.875rem;">
                                👁️ View
                            </a>
                            <a href="{{ route('workouts.edit', $related) }}" class="btn-primary" style="padding: 5px 10px; font-size: 0.875rem;">
                                ✏️ Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Navigation Links -->
    <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
        <div style="color: #718096; margin-bottom: 15px;">
            💪 Keep up the great work! Every workout counts towards your fitness goals.
        </div>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('workouts.index', ['exercise' => $workout->exercise]) }}" class="btn-primary">
                🔍 All {{ $workout->exercise }} Workouts
            </a>
            <a href="{{ route('workouts.create') }}" class="btn-secondary">
                ➕ Log Another Workout
            </a>
            <a href="{{ route('workouts.index') }}" class="btn-secondary">
                📊 View All Workouts
            </a>
        </div>
    </div>
@endsection
