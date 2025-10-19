<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date|before_or_equal:today',
            'exercise' => 'required|string|max:100',
            'duration' => 'required|integer|min:1|max:480', // 1 minute to 8 hours
            'calories' => 'nullable|integer|min:1|max:5000',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'date.required' => 'Please select a workout date.',
            'date.date' => 'Please enter a valid date.',
            'date.before_or_equal' => 'The workout date cannot be in the future.',
            'exercise.required' => 'Please enter the exercise name.',
            'exercise.max' => 'Exercise name cannot exceed 100 characters.',
            'duration.required' => 'Please enter the workout duration.',
            'duration.integer' => 'Duration must be a whole number.',
            'duration.min' => 'Workout duration must be at least 1 minute.',
            'duration.max' => 'Workout duration cannot exceed 8 hours.',
            'calories.integer' => 'Calories must be a whole number.',
            'calories.min' => 'Calories cannot be negative.',
            'calories.max' => 'Calories burned cannot exceed 5000.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'date' => 'workout date',
            'exercise' => 'exercise name',
            'duration' => 'workout duration',
            'calories' => 'calories burned',
            'notes' => 'workout notes',
        ];
    }
}
