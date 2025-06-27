<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = auth()->user()->id;
        
        $query = Reservation::whereHas('doctor', function($q) use ($doctorId) {
            $q->where('user_id', $doctorId);
        })->with(['patient', 'doctor']);

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $reservations = $query->orderBy('reservation_date', 'desc')
                             ->orderBy('reservation_time', 'desc')
                             ->paginate(10);

        // Get statistics
        $todayReservations = Reservation::whereHas('doctor', function($q) use ($doctorId) {
            $q->where('user_id', $doctorId);
        })->whereDate('reservation_date', today())->count();

        $pendingReservations = Reservation::whereHas('doctor', function($q) use ($doctorId) {
            $q->where('user_id', $doctorId);
        })->where('status', 'pending')->count();

        $confirmedReservations = Reservation::whereHas('doctor', function($q) use ($doctorId) {
            $q->where('user_id', $doctorId);
        })->where('status', 'confirmed')->count();

        $completedReservations = Reservation::whereHas('doctor', function($q) use ($doctorId) {
            $q->where('user_id', $doctorId);
        })->where('status', 'completed')->count();

        return view('doctor.reservations.index', compact(
            'reservations', 
            'todayReservations', 
            'pendingReservations', 
            'confirmedReservations', 
            'completedReservations'
        ));
    }

    public function show(Reservation $reservation)
    {
        // Verify this doctor has access to this reservation
        $doctorId = auth()->user()->id;
        if ($reservation->doctor->user_id !== $doctorId) {
            abort(403, 'You do not have access to this reservation.');
        }

        return view('doctor.reservations.show', compact('reservation'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        // Verify this doctor has access to this reservation
        $doctorId = auth()->user()->id;
        if ($reservation->doctor->user_id !== $doctorId) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $reservation->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
}
