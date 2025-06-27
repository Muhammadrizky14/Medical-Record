<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\MedicalRecord;
use App\Models\Doctor;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get or create the doctor record associated with this user
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor) {
            // If no doctor record exists, create one
            $doctor = Doctor::create([
                'name' => $user->name,
                'specialization' => $user->specialization ?? 'General Medicine',
                'phone' => $user->phone ?? '',
                'email' => $user->email,
                'address' => $user->address ?? '',
                'user_id' => $user->id,
            ]);
        }
        
        // Get statistics for this doctor
        $stats = [
            'patients' => Patient::where(function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id)
                      ->orWhereHas('reservations', function($q) use ($doctor) {
                          $q->where('doctor_id', $doctor->id);
                      });
            })->distinct()->count(),
            
            'reservations' => Reservation::where('doctor_id', $doctor->id)->count(),
            
            'medical_records' => MedicalRecord::where('doctor_id', $doctor->id)->count(),
        ];

        // Get recent patients (last 5)
        $recentPatients = Patient::where(function($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id)
                  ->orWhereHas('reservations', function($q) use ($doctor) {
                      $q->where('doctor_id', $doctor->id);
                  });
        })->distinct()->orderBy('created_at', 'desc')->limit(5)->get();

        // Get recent medical records (last 5)
        $recentRecords = MedicalRecord::where('doctor_id', $doctor->id)
                                    ->with('patient')
                                    ->orderBy('visit_date', 'desc')
                                    ->limit(5)
                                    ->get();

        return view('doctor.dashboard', compact('stats', 'recentPatients', 'recentRecords'));
    }
}
