<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor) {
            $medicalRecords = collect();
        } else {
            $query = MedicalRecord::where('doctor_id', $doctor->id)->with(['patient', 'doctor']);

            // Search functionality
            if ($request->filled('search')) {
                $query->whereHas('patient', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            }

            // Date range filter
            if ($request->filled('date_from')) {
                $query->whereDate('visit_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('visit_date', '<=', $request->date_to);
            }

            $medicalRecords = $query->orderBy('visit_date', 'desc')->paginate(10);
        }

        return view('doctor.medical-records.index', compact('medicalRecords'));
    }

    public function create(Request $request)
    {
        $patients = Patient::all();
        $selectedPatient = null;
        
        if ($request->has('patient_id')) {
            $selectedPatient = Patient::find($request->patient_id);
        }

        return view('doctor.medical-records.create', compact('patients', 'selectedPatient'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnosis' => 'required|string',
            'symptoms' => 'required|string',
            'treatment' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'visit_date' => 'required|date',
        ]);

        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor record not found.');
        }
        
        MedicalRecord::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $doctor->id,
            'diagnosis' => $request->diagnosis,
            'symptoms' => $request->symptoms,
            'treatment' => $request->treatment,
            'prescription' => $request->prescription,
            'notes' => $request->notes,
            'visit_date' => $request->visit_date,
        ]);

        return redirect()->route('doctor.medical-records.index')
                        ->with('success', 'Medical record created successfully');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        // Verify this doctor has access to this record
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor || $medicalRecord->doctor_id !== $doctor->id) {
            abort(403, 'You do not have access to this medical record.');
        }

        return view('doctor.medical-records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        // Verify this doctor has access to this record
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor || $medicalRecord->doctor_id !== $doctor->id) {
            abort(403, 'You do not have access to this medical record.');
        }

        $patients = Patient::all();
        
        return view('doctor.medical-records.edit', compact('medicalRecord', 'patients'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnosis' => 'required|string',
            'symptoms' => 'required|string',
            'treatment' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'visit_date' => 'required|date',
        ]);

        // Verify this doctor has access to this record
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor || $medicalRecord->doctor_id !== $doctor->id) {
            abort(403, 'You do not have access to this medical record.');
        }

        $medicalRecord->update($request->all());

        return redirect()->route('doctor.medical-records.index')
                        ->with('success', 'Medical record updated successfully');
    }
public function print(MedicalRecord $medicalRecord)
{
    // Verify this doctor has access to this record
    $user = auth()->user();
    $doctor = Doctor::where('user_id', $user->id)->first();
    
    if (!$doctor || $medicalRecord->doctor_id !== $doctor->id) {
        abort(403, 'You do not have access to this medical record.');
    }
    
    return view('doctor.medical-records.print', compact('medicalRecord'));
}
}
