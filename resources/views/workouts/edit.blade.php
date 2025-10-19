@extends('layouts.app')

@section('title', 'Edit Workout - Fitness Tracker')

@section('description', 'Update your workout details. Modify exercise information, duration, calories, and personal notes.')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">✏️ Edit Workout</h1>
            <p class="page-subtitle">Update your {{ $workout->exercise }} workout from {{ $workout->formatted_date }}</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('workouts.show', $workout) }}" class="btn-secondary">
                👁️ View Details
            </a>
            <a href="{{ route('workouts.index') }}" class="btn-secondary">
                ↪️ Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Workout Form -->
    <div class="card">
        <form action="{{ route('workouts.update', $workout) }}" method="POST" style="max-width: 800px; margin: 0 auto;">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px;">
                <!-- Left Column -->
                <div>
                    <!-- Date Field -->
                    <div class="form-group">
                        <label for="date" class="form-label">
                            📅 Workout Date *
                        </label>
                        <input type="date" 
                               id="date" 
                               name="date" 
                               class="form-input {{ $errors && $errors->has('date') ? 'border-red-500' : '' }}"
                               value="{{ old('date', $workout->date->format('Y-m-d')) }}"
                               required>
                        @if($errors && $errors->has('date'))
                            <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">
                                {{ $errors->first('date') }}
                            </div>
                        @endif
                    </div>

                    <!-- Exercise Field -->
                    <div class="form-group">
                        <label for="exercise" class="form-label">
                            💪 Exercise/Activity *
                        </label>
                        <input type="text" 
                               id="exercise" 
                               name="exercise" 
                               class="form-input {{ $errors && $errors->has('exercise') ? 'border-red-500' : '' }}"
                               value="{{ old('exercise', $workout->exercise) }}"
                               placeholder="e.g., Morning Jogging, Weight Training, Yoga..."
                               required>
                        @if($errors && $errors->has('exercise'))
                            <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">
                                {{ $errors->first('exercise') }}
                            </div>
                        @endif
                        <div style="font-size: 0.875rem; color: #718096; margin-top: 5px;">
                            💡 Tip: Be specific about your exercise for better tracking!
                        </div>
                    </div>

                    <!-- Duration Field -->
                    <div class="form-group">
                        <label for="duration" class="form-label">
                            ⏱️ Duration (minutes) *
                        </label>
                        <input type="number" 
                               id="duration" 
                               name="duration" 
                               class="form-input {{ $errors && $errors->has('duration') ? 'border-red-500' : '' }}"
                               value="{{ old('duration', $workout->duration) }}"
                               placeholder="30"
                               min="1"
                               max="480"
                               required>
                        @if($errors && $errors->has('duration'))
                            <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">
                                {{ $errors->first('duration') }}
                            </div>
                        @endif
                        <div style="font-size: 0.875rem; color: #718096; margin-top: 5px;">
                            ⏰ How long did your workout last? (1 minute to 8 hours max)
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Calories Field -->
                    <div class="form-group">
                        <label for="calories" class="form-label">
                            🔥 Calories Burned
                        </label>
                        <input type="number" 
                               id="calories" 
                               name="calories" 
                               class="form-input {{ $errors && $errors->has('calories') ? 'border-red-500' : '' }}"
                               value="{{ old('calories', $workout->calories) }}"
                               placeholder="300"
                               min="1"
                               max="5000">
                        @if($errors && $errors->has('calories'))
                            <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">
                                {{ $errors->first('calories') }}
                            </div>
                        @endif
                        <div style="font-size: 0.875rem; color: #718096; margin-top: 5px;">
                            🔋 Optional: Use a fitness tracker or estimate calories burned
                        </div>
                    </div>

                    <!-- Notes Field -->
                    <div class="form-group">
                        <label for="notes" class="form-label">
                            📝 Workout Notes
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  class="form-input form-textarea {{ $errors && $errors->has('notes') ? 'border-red-500' : '' }}"
                                  placeholder="How did you feel? Any achievements? Personal records? Interesting observations..."
                                  rows="6">{{ old('notes', $workout->notes) }}</textarea>
                        @if($errors && $errors->has('notes'))
                            <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">
                                {{ $errors->first('notes') }}
                            </div>
                        @endif
                        <div style="font-size: 0.875rem; color: #718096; margin-top: 5px;">
                            📚 Optional: Note how you felt, achievements, personal records, or any interesting observations
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Workout Info -->
            <div style="background: #f7fafc; border-radius: 8px; padding: 20px; margin-bottom: 30px;">
                <h4 style="color: #4a5568; margin-bottom: 15px;">📊 Current Workout Stats</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    <div>
                        <strong>Duration:</strong>{{ $workout->formatted_duration }}
                    </div>
                    @if($workout->calories)
                        <div>
                            <strong>Calories:</strong>{{ number_format($workout->calories) }} cal
                        </div>
                        <div>
                            <strong>Efficiency:</strong>{{ $workout->calories_per_minute }} cal/min
                        </div>
                    @endif
                    <div>
                        <strong>Created:</strong>{{ $workout->created_at->diffForHumans() }}
                    </div>
                    @if($workout->updated_at != $workout->created_at)
                        <div>
                            <strong>Last Updated:</strong>{{ $workout->updated_at->diffForHumans() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Form Actions -->
            <div style="border-top: 2px solid #e2e8f0; padding-top: 30px;">
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <button type="submit" class="btn-success">
                        💾 Update Workout
                    </button>
                    <a href="{{ route('workouts.show', $workout) }}" class="btn-secondary">
                        👁️ View Workout
                    </a>
                    <a href="{{ route('workouts.index') }}" class="btn-secondary">
                        ❌ Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Related Workouts -->
    @if($relatedWorkouts->count() > 0)
        <div style="background: white; border-radius: 12px; padding: 30px; margin-top: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <h3 style="color: #4a5568; margin-bottom: 20px;">🏃‍♀️ Similar Workouts</h3>
            <p style="color: #718096; margin-bottom: 20px;">Other workouts with similar exercises:</p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                @foreach($relatedWorkouts as $related)
                    <div style="background: #f7fafc; border-radius: 8px; padding: 15px;">
                        <div style="font-weight: 600; color: #667eea; margin-bottom: 5px;">
                            {{ $related->exercise }}
                        </div>
                        <div style="font-size: 0.875rem; color: #718096;">
                            {{ $related->formatted_date }} • {{ $related->formatted_duration }}
                            @if($related->calories)
                                • {{ number_format($related->calories) }} cal
                            @endif
                        </div>
                        <div style="margin-top: 10px;">
                            <a href="{{ route('workouts.show', $related) }}" class="btn-secondary" style="padding: 5px 10px; font-size: 0.875rem;">
                                👁️ View
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    // Auto-calculate estimated calories based on exercise type and duration
    const exerciseCalorieEstimates = {
        'Running': 10,      // calories per minute
        'Weight Training': 6,
        'Yoga': 3,
        'Cycling': 8,
        'Swimming': 9,
        'HIIT': 12,
        'Walking': 4,
        'Pilates': 3.5,
        'Boxing': 10,
        'Tennis': 7,
        'Basketball': 8,
        'Dancing': 5
    };

    document.getElementById('exercise').addEventListener('input', function() {
        const exercise = this.value;
        const duration = document.getElementById('duration').value;
        
        // Check if exercise matches any of our estimates (case insensitive)
        const exerciseMatch = Object.keys(exerciseCalorieEstimates).find(key => 
            exercise.toLowerCase().includes(key.toLowerCase())
        );
        
        if (exerciseMatch && duration) {
            const estimatedCalories = Math.round(duration * exerciseCalorieEstimates[exerciseMatch]);
            if (estimatedCalories <= 5000) { // Max allowed calories
                document.getElementById('calories').value = estimatedCalories;
                
                // Show a hint to the user
                const caloriesField = document.getElementById('calories');
                caloriesField.style.borderColor = '#38a169';
                setTimeout(() => {
                    caloriesField.style.borderColor = '#e2e8f0';
                }, 2000);
            }
        }
    });

    document.getElementById('duration').addEventListener('input', function() {
        const exercise = document.getElementById('exercise').value;
        const duration = this.value;
        
        const exerciseMatch = Object.keys(exerciseCalorieEstimates).find(key => 
            exercise.toLowerCase().includes(key.toLowerCase())
        );
        
        if (exerciseMatch && duration) {
            const estimatedCalories = Math.round(duration * exerciseCalorieEstimates[exerciseMatch]);
            if (estimatedCalories <= 5000) {
                document.getElementById('calories').value = estimatedCalories;
            }
        }
    });
</script>
@endpush
