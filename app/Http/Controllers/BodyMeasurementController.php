<?php

namespace App\Http\Controllers;

use App\Models\BodyMeasurement;
use App\Models\User;
use Illuminate\Http\Request;

class BodyMeasurementController extends Controller
{
    /**
     * Display a listing of body measurements.
     */
    public function index(Request $request)
    {
        $query = BodyMeasurement::with('user');

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->where('measurement_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->where('measurement_date', '<=', $request->end_date);
        }

        $bodyMeasurements = $query->orderBy('measurement_date', 'desc')->paginate(10);
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('body-measurements.index', compact('bodyMeasurements', 'users'));
    }

    /**
     * Show the form for creating a new body measurement.
     */
    public function create()
    {
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();
        return view('body-measurements.create', compact('users'));
    }

    /**
     * Store a newly created body measurement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'measurement_date' => 'required|date',
            'weight_kg' => 'nullable|numeric|min:20|max:500',
            'body_fat_percentage' => 'nullable|numeric|min:0|max:100',
            'muscle_mass_kg' => 'nullable|numeric|min:0|max:200',
            'chest_cm' => 'nullable|numeric|min:0|max:200',
            'waist_cm' => 'nullable|numeric|min:0|max:200',
            'hips_cm' => 'nullable|numeric|min:0|max:200',
            'thigh_cm' => 'nullable|numeric|min:0|max:200',
            'arm_cm' => 'nullable|numeric|min:0|max:200',
            'neck_cm' => 'nullable|numeric|min:0|max:200',
            'bmi' => 'nullable|numeric|min:10|max:100',
            'notes' => 'nullable|string',
        ]);

        // Calculate BMI if not provided but weight and height are available
        if (!$validated['bmi'] && $validated['weight_kg']) {
            $user = User::find($validated['user_id']);
            if ($user && $user->height_cm) {
                $heightInMeters = $user->height_cm / 100;
                $validated['bmi'] = round($validated['weight_kg'] / ($heightInMeters * $heightInMeters), 2);
            }
        }

        $bodyMeasurement = BodyMeasurement::create($validated);

        return redirect()->route('body-measurements.show', $bodyMeasurement)
            ->with('success', 'Body measurement created successfully.');
    }

    /**
     * Display the specified body measurement.
     */
    public function show(BodyMeasurement $bodyMeasurement)
    {
        $bodyMeasurement->load('user');
        return view('body-measurements.show', compact('bodyMeasurement'));
    }

    /**
     * Show the form for editing the body measurement.
     */
    public function edit(BodyMeasurement $bodyMeasurement)
    {
        $users = User::select('id', 'username', 'first_name', 'last_name')->get();
        return view('body-measurements.edit', compact('bodyMeasurement', 'users'));
    }

    /**
     * Update the specified body measurement.
     */
    public function update(Request $request, BodyMeasurement $bodyMeasurement)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'measurement_date' => 'required|date',
            'weight_kg' => 'nullable|numeric|min:20|max:500',
            'body_fat_percentage' => 'nullable|numeric|min:0|max:100',
            'muscle_mass_kg' => 'nullable|numeric|min:0|max:200',
            'chest_cm' => 'nullable|numeric|min:0|max:200',
            'waist_cm' => 'nullable|numeric|min:0|max:200',
            'hips_cm' => 'nullable|numeric|min:0|max:200',
            'thigh_cm' => 'nullable|numeric|min:0|max:200',
            'arm_cm' => 'nullable|numeric|min:0|max:200',
            'neck_cm' => 'nullable|numeric|min:0|max:200',
            'bmi' => 'nullable|numeric|min:10|max:100',
            'notes' => 'nullable|string',
        ]);

        // Calculate BMI if not provided but weight and height are available
        if (!$validated['bmi'] && $validated['weight_kg']) {
            $user = User::find($validated['user_id']);
            if ($user && $user->height_cm) {
                $heightInMeters = $user->height_cm / 100;
                $validated['bmi'] = round($validated['weight_kg'] / ($heightInMeters * $heightInMeters), 2);
            }
        }

        $bodyMeasurement->update($validated);

        return redirect()->route('body-measurements.show', $bodyMeasurement)
            ->with('success', 'Body measurement updated successfully.');
    }

    /**
     * Remove the specified body measurement.
     */
    public function destroy(BodyMeasurement $bodyMeasurement)
    {
        $bodyMeasurement->delete();

        return redirect()->route('body-measurements.index')
            ->with('success', 'Body measurement deleted successfully.');
    }

    /**
     * Show body measurement trends for a user.
     */
    public function trends(Request $request)
    {
        $userId = $request->get('user_id');

        if (!$userId) {
            return redirect()->route('body-measurements.index')
                ->with('error', 'Please select a user to view trends.');
        }

        $user = User::findOrFail($userId);
        
        $measurements = BodyMeasurement::where('user_id', $userId)
            ->orderBy('measurement_date')
            ->get();

        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('body-measurements.trends', compact('user', 'measurements', 'users'));
    }

    /**
     * Show body measurement statistics.
     */
    public function statistics(Request $request)
    {
        $query = BodyMeasurement::query();

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->where('measurement_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->where('measurement_date', '<=', $request->end_date);
        }

        $stats = [
            'total_measurements' => $query->count(),
            'avg_weight' => $query->avg('weight_kg'),
            'avg_body_fat' => $query->avg('body_fat_percentage'),
            'avg_muscle_mass' => $query->avg('muscle_mass_kg'),
            'avg_bmi' => $query->avg('bmi'),
        ];

        $bmiDistribution = $query->selectRaw('
            CASE 
                WHEN bmi < 18.5 THEN "Underweight"
                WHEN bmi < 25 THEN "Normal"
                WHEN bmi < 30 THEN "Overweight"
                ELSE "Obese"
            END as bmi_category,
            COUNT(*) as count
        ')
        ->groupBy('bmi_category')
        ->get();

        $users = User::select('id', 'username', 'first_name', 'last_name')->get();

        return view('body-measurements.statistics', compact('stats', 'bmiDistribution', 'users'));
    }
}
