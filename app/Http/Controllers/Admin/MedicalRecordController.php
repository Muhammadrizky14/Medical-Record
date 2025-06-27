<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Reservation;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $medicalRecords = MedicalRecord::with(['patient', 'doctor', 'reservation'])->paginate(10);
        return view('admin.medical-records.index', compact('medicalRecords'));
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $reservations = Reservation::where('status', 'confirmed')->get();
        return view('admin.medical-records.create', compact('patients', 'doctors', 'reservations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'reservation_id' => 'nullable|exists:reservations,id',
            'diagnosis' => 'required|string',
            'symptoms' => 'required|string',
            'treatment' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'visit_date' => 'required|date',
        ]);

        MedicalRecord::create($request->all());

        return redirect()->route('admin.medical-records.index')->with('success', 'Medical record created successfully');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        return view('admin.medical-records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $reservations = Reservation::where('status', 'confirmed')->get();
        return view('admin.medical-records.edit', compact('medicalRecord', 'patients', 'doctors', 'reservations'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'reservation_id' => 'nullable|exists:reservations,id',
            'diagnosis' => 'required|string',
            'symptoms' => 'required|string',
            'treatment' => 'required|string',
            'prescription' => 'nullable|string',
            'notes' => 'nullable|string',
            'visit_date' => 'required|date',
        ]);

        $medicalRecord->update($request->all());

        return redirect()->route('admin.medical-records.index')->with('success', 'Medical record updated successfully');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();
        return redirect()->route('admin.medical-records.index')->with('success', 'Medical record deleted successfully');
    }
}
