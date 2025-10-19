<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\WorkoutSessionController;
use App\Http\Controllers\WorkoutExerciseController;
use App\Http\Controllers\ExerciseTypeController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\NutritionLogController;
use App\Http\Controllers\BodyMeasurementController;
use App\Http\Controllers\WorkoutPlanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Main Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/workout-analytics', [DashboardController::class, 'workoutAnalytics'])->name('dashboard.workout-analytics');
Route::get('/dashboard/nutrition-analytics', [DashboardController::class, 'nutritionAnalytics'])->name('dashboard.nutrition-analytics');
Route::get('/dashboard/goal-analytics', [DashboardController::class, 'goalAnalytics'])->name('dashboard.goal-analytics');

// Users
Route::resource('users', UserController::class);
Route::get('/users/{user}/dashboard', [UserController::class, 'dashboard'])->name('users.dashboard');

// Workout Sessions (New detailed workouts)
Route::resource('workout-sessions', WorkoutSessionController::class);
Route::get('/workout-sessions/{workoutSession}/statistics', [WorkoutSessionController::class, 'statistics'])->name('workout-sessions.statistics');

// Workout Exercises
Route::resource('workout-exercises', WorkoutExerciseController::class);
Route::get('/workout-exercises/{workoutExercise}/statistics', [WorkoutExerciseController::class, 'statistics'])->name('workout-exercises.statistics');

// Exercise Types
Route::resource('exercise-types', ExerciseTypeController::class);
Route::get('/exercise-types/{exerciseType}/statistics', [ExerciseTypeController::class, 'statistics'])->name('exercise-types.statistics');

// Goals
Route::resource('goals', GoalController::class);
Route::post('/goals/{goal}/update-progress', [GoalController::class, 'updateProgress'])->name('goals.update-progress');
Route::get('/goals/{goal}/statistics', [GoalController::class, 'statistics'])->name('goals.statistics');

// Nutrition Logs
Route::resource('nutrition-logs', NutritionLogController::class);
Route::get('/nutrition-logs/daily-summary', [NutritionLogController::class, 'dailySummary'])->name('nutrition-logs.daily-summary');
Route::get('/nutrition-logs/{nutritionLog}/statistics', [NutritionLogController::class, 'statistics'])->name('nutrition-logs.statistics');

// Body Measurements
Route::resource('body-measurements', BodyMeasurementController::class);
Route::get('/body-measurements/trends', [BodyMeasurementController::class, 'trends'])->name('body-measurements.trends');
Route::get('/body-measurements/{bodyMeasurement}/statistics', [BodyMeasurementController::class, 'statistics'])->name('body-measurements.statistics');

// Workout Plans
Route::resource('workout-plans', WorkoutPlanController::class);
Route::post('/workout-plans/{workoutPlan}/copy-template', [WorkoutPlanController::class, 'copyTemplate'])->name('workout-plans.copy-template');
Route::get('/workout-plans/{workoutPlan}/statistics', [WorkoutPlanController::class, 'statistics'])->name('workout-plans.statistics');

// Legacy Workouts (Simple workouts)
Route::resource('workouts', WorkoutController::class);
Route::get('/workouts/{workout}/statistics', [WorkoutController::class, 'statistics'])->name('workouts.statistics');
