<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['patient', 'doctor', 'schedule'])->paginate(10);
        return view('admin.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $schedules = Schedule::where('is_available', true)->get();
        return view('admin.reservations.create', compact('patients', 'doctors', 'schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        Reservation::create($request->all());

        return redirect()->route('admin.reservations.index')->with('success', 'Reservation created successfully');
    }

    public function show(Reservation $reservation)
    {
        return view('admin.reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $schedules = Schedule::where('is_available', true)->get();
        return view('admin.reservations.edit', compact('reservation', 'patients', 'doctors', 'schedules'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $reservation->update($request->all());

        return redirect()->route('admin.reservations.index')->with('success', 'Reservation updated successfully');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('admin.reservations.index')->with('success', 'Reservation deleted successfully');
    }
}
