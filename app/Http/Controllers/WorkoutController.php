<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Http\Requests\StoreWorkoutRequest;
use App\Http\Requests\UpdateWorkoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Workout::query();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('exercise', 'like', '%' . $search . '%')
                  ->orWhere('notes', 'like', '%' . $search . '%')
                  ->orWhereDate('date', $search);
            });
        }

        // Apply date filter
        if ($request->filled('date_filter')) {
            $dateFilter = $request->input('date_filter');
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('date', today());
                    break;
                case 'week':
                    $query->recent(7);
                    break;
                case 'month':
                    $query->whereDate('date', '>=', now()->subMonth());
                    break;
                case 'custom':
                    if ($request->filled('start_date') && $request->filled('end_date')) {
                        $query->dateRange($request->input('start_date'), $request->input('end_date'));
                    }
                    break;
            }
        }

        // Apply minimum duration filter
        if ($request->filled('min_duration')) {
            $query->minimumDuration($request->input('min_duration'));
        }

        // Get statistics before pagination
        $stats = $this->getWorkoutStats($query->getQuery());

        // Order by date descending and paginate
        $workouts = $query->orderBy('date', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10)
                         ->with(['search' => $request->input('search')]);

        return view('workouts.index', compact('workouts', 'stats'))
            ->with('request', $request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('workouts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkoutRequest $request)
    {
        try {
            $workout = Workout::create($request->validated());
            
            return redirect()
                ->route('workouts.index')
                ->with('success', "Great job! Your {$workout->exercise} workout has been logged successfully.");
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Sorry, there was an error saving your workout. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Workout $workout)
    {
        $relatedWorkouts = Workout::where('exercise', 'like', '%' . $workout->exercise . '%')
                                  ->where('id', '!=', $workout->id)
                                  ->orderBy('date', 'desc')
                                  ->limit(5)
                                  ->get();

        return view('workouts.show', compact('workout', 'relatedWorkouts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workout $workout)
    {
        $relatedWorkouts = Workout::where('exercise', 'like', '%' . $workout->exercise . '%')
                                  ->where('id', '!=', $workout->id)
                                  ->orderBy('date', 'desc')
                                  ->limit(5)
                                  ->get();

        return view('workouts.edit', compact('workout', 'relatedWorkouts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkoutRequest $request, Workout $workout)
    {
        try {
            $workout->update($request->validated());
            
            return redirect()
                ->route('workouts.index')
                ->with('success', "Your {$workout->exercise} workout has been updated successfully.");
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Sorry, there was an error updating your workout. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workout $workout)
    {
        try {
            $exerciseName = $workout->exercise;
            $workout->delete();
            
            return redirect()
                ->route('workouts.index')
                ->with('success', "Your {$exerciseName} workout has been deleted successfully.");
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Sorry, there was an error deleting your workout. Please try again.');
        }
    }

    /**
     * Get workout statistics for the dashboard.
     */
    private function getWorkoutStats($baseQuery)
    {
        $stats = [];

        // Clone the query to avoid modifying the original
        $clonedQuery = clone $baseQuery;
        
        $stats['total_workouts'] = $clonedQuery->count();
        
        if ($stats['total_workouts'] > 0) {
            $clonedQuery2 = clone $baseQuery;
            $clonedQuery3 = clone $baseQuery;
            $clonedQuery4 = clone $baseQuery;
            
            $stats['total_duration'] = $clonedQuery2->sum('duration');
            $stats['total_calories'] = $clonedQuery3->sum('calories');
            $stats['average_duration'] = round($clonedQuery4->avg('duration'), 1);
            
            // Most popular exercise this period
            $clonedQuery5 = clone $baseQuery;
            $popularExercise = $clonedQuery5->select('exercise', DB::raw('count(*) as count'))
                                           ->groupBy('exercise')
                                           ->orderBy('count', 'desc')
                                           ->first();
            
            $stats['popular_exercise'] = $popularExercise ? $popularExercise->exercise : 'None';
        } else {
            $stats['total_duration'] = 0;
            $stats['total_calories'] = 0;
            $stats['average_duration'] = 0;
            $stats['popular_exercise'] = 'None';
        }

        return $stats;
    }

    /**
     * Export workouts data (bonus feature).
     */
    public function export()
    {
        $workouts = Workout::orderBy('date', 'desc')->get();
        
        $csvData = [];
        $csvData[] = ['Date', 'Exercise', 'Duration (minutes)', 'Calories', 'Notes'];
        
        foreach ($workouts as $workout) {
            $csvData[] = [
                $workout->date->format('Y-m-d'),
                $workout->exercise,
                $workout->duration,
                $workout->calories ?? 'N/A',
                $workout->notes ?? 'N/A'
            ];
        }
        
        return response()->stream(function() use ($csvData) {
            $handle = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="fitness-tracker-' . date('Y-m-d') . '.csv"',
        ]);
    }
}
