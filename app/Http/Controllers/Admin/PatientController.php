<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with(['medicalRecords' => function($query) {
            $query->orderBy('visit_date', 'desc');
        }])->paginate(10);
        
        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'medical_history' => 'nullable|string',
        ]);

        Patient::create($request->all());

        return redirect()->route('admin.patients.index')->with('success', 'Patient created successfully');
    }

    public function show(Patient $patient)
    {
        $patient->load(['reservations', 'medicalRecords' => function($query) {
            $query->orderBy('visit_date', 'desc');
        }]);
        
        return view('admin.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'medical_history' => 'nullable|string',
        ]);

        $patient->update($request->all());

        return redirect()->route('admin.patients.index')->with('success', 'Patient updated successfully');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('admin.patients.index')->with('success', 'Patient deleted successfully');
    }
}
