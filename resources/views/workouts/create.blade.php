@extends('layouts.app')

@section('title', 'Add New Workout - Fitness Tracker')

@section('description', 'Log a new workout session. Track your exercises, duration, calories burned, and add personal notes.')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">➕ Log New Workout</h1>
            <p class="page-subtitle">Keep track of your fitness journey one workout at a time!</p>
        </div>
        <a href="{{ route('workouts.index') }}" class="btn-secondary">
            ↪️ Back to Dashboard
        </a>
    </div>

    <!-- Workout Form -->
    <div class="card">
        <form action="{{ route('workouts.store') }}" method="POST" style="max-width: 800px; margin: 0 auto;">
            @csrf
            
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
                               class="form-input {{ $errors->has('date') ? 'border-red-500' : '' }}"
                               value="{{ old('date', date('Y-m-d')) }}"
                               required>
                        @error('date')
                            <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Exercise Field -->
                    <div class="form-group">
                        <label for="exercise" class="form-label">
                            💪 Exercise/Activity *
                        </label>
                        <input type="text" 
                               id="exercise" 
                               name="exercise" 
                               class="form-input {{ $errors->has('exercise') ? 'border-red-500' : '' }}"
                               value="{{ old('exercise') }}"
                               placeholder="e.g., Morning Jogging, Weight Training, Yoga..."
                               required>
                        @error('exercise')
                            <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">
                                {{ $message }}
                            </div>
                        @enderror
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
                               class="form-input {{ $errors->has('duration') ? 'border-red-500' : '' }}"
                               value="{{ old('duration') }}"
                               placeholder="30"
                               min="1"
                               max="480"
                               required>
                        @error('duration')
                            <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">
                                {{ $message }}
                            </div>
                        @enderror
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
                               class="form-input {{ $errors->has('calories') ? 'border-red-500' : '' }}"
                               value="{{ old('calories') }}"
                               placeholder="300"
                               min="1"
                               max="5000">
                        @error('calories')
                            <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">
                                {{ $message }}
                            </div>
                        @enderror
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
                                  class="form-input form-textarea {{ ${$errors->has('notes') ? 'border-red-500' : '' }}"
                                  placeholder="How did you feel? Any achievements? Personal records? Interesting observations..."
                                  rows="6">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div style="color: #e53e3e; font-size: 0.875rem; margin-top: 5px;">
                                {{ $message }}
                            </div>
                        @enderror
                        <div style="font-size: 0.875rem; color: #718096; margin-top: 5px;">
                            📚 Optional: Note how you felt, achievements, personal records, or any interesting observations
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="border-top: 2px solid #e2e8f0; padding-top: 30px; text-align: center;">
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <button type="submit" class="btn-success">
                        💾 Save Workout
                    </button>
                    <a href="{{ route('workouts.index') }}" class="btn-secondary">
                        ❌ Cancel
                    </a>
                    <button type="reset" class="btn-secondary">
                        🔄 Reset Form
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Quick Tips -->
    <div style="background: #f0fff4; border: 1px solid #9ae6b4; border-radius: 12px; padding: 20px; margin-top: 30px;">
        <h3 style="color: #22543d; margin-bottom: 15px;">💡 Quick Tips for Better Tracking</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;">
            <div>
                <strong>📊 Consistency:</strong> Log workouts immediately or set reminders to ensure accurate tracking.
            </div>
            <div>
                <strong>🔥 Calories:</strong> Use fitness apps, heart rate monitors, or reliable online calculators.
            </div>
            <div>
                <strong>📝 Notes:</strong> Record how you felt, energy levels, and any progress you noticed.
            </div>
            <div>
                <strong>🏃‍♀️ Variety:</strong> Track different exercise types to see patterns in your fitness journey.
            </div>
        </div>
    </div>

    <!-- Popular Exercises -->
    <div style="background: white; border-radius: 12px; padding: 30px; margin-top: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
        <h3 style="color: #4a5568; margin-bottom: 20px;">🏆 Popular Exercise Types</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px;">
            <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px; cursor: pointer;" 
                 onclick="document.querySelector('input[name=\"exercise\"]').value='Running'">
                <div style="font-size: 1.5rem; margin-bottom: 5px;">🏃‍♂️</div>
                <div>Running</div>
            </div>
            <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px; cursor: pointer;" 
                 onclick="document.querySelector('input[name=\"exercise\"]').value='Weight Training'">
                <div style="font-size: 1.5rem; margin-bottom: 5px;">🏋️</div>
                <div>Weight Training</div>
            </div>
            <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px; cursor: pointer;" 
                 onclick="document.querySelector('input[name=\"exercise\"]').value='Yoga'">
                <div style="font-size: 1.5rem; margin-bottom: 5px;">🧘‍♀️</div>
                <div>Yoga</div>
            </div>
            <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px; cursor: pointer;" 
                 onclick="document.querySelector('input[name=\"exercise\"]').value='Cycling'">
                <div style="font-size: 1.5rem; margin-bottom: 5px;">🚴‍♂️</div>
                <div>Cycling</div>
            </div>
            <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px; cursor: pointer;" 
                 onclick="document.querySelector('input[name=\"exercise\"]').value='Swimming'">
                <div style="font-size: 1.5rem; margin-bottom: 5px;">🏊‍♀️</div>
                <div>Swimming</div>
            </div>
            <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px; cursor: pointer;" 
                 onclick="document.querySelector('input[name=\"exercise\"]').value='HIIT'">
                <div style="font-size: 1.5rem; margin-bottom: 5px;">⚡</div>
                <div>HIIT</div>
            </div>
            <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px; cursor: pointer;" 
                 onclick="document.querySelector('input[name=\"exercise\"]').value='Walking'">
                <div style="font-size: 1.5rem; margin-bottom: 5px;">🚶‍♀️</div>
                <div>Walking</div>
            </div>
            <div style="text-align: center; padding: 15px; background: #f7fafc; border-radius: 8px; cursor: pointer;" 
                 onclick="document.querySelector('input[name=\"exercise\"]').value='Pilates'">
                <div style="font-size: 1.5rem; margin-bottom: 5px;">🤸‍♀️</div>
                <div>Pilates</div>
            </div>
        </div>
    </div>
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
