<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Reservation;
use App\Models\MedicalRecord;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'doctors' => Doctor::count(),
            'patients' => Patient::count(),
            'schedules' => Schedule::count(),
            'reservations' => Reservation::count(),
            'medical_records' => MedicalRecord::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
