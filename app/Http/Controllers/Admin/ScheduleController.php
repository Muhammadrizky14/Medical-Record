<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['doctor', 'user'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $doctors = Doctor::all();
        Log::info('Schedule create page accessed', ['doctors_count' => $doctors->count()]);
        return view('admin.schedules.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        Log::info('Schedule store method called', [
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        try {
            // Validate the request
            $validatedData = $request->validate([
                'doctor_id' => 'required|exists:doctors,id',
                'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'is_available' => 'nullable|boolean',
            ], [
                'doctor_id.required' => 'Please select a doctor',
                'doctor_id.exists' => 'Selected doctor does not exist',
                'day_of_week.required' => 'Please select a day of the week',
                'day_of_week.in' => 'Invalid day of the week selected',
                'start_time.required' => 'Start time is required',
                'start_time.date_format' => 'Start time must be in HH:MM format',
                'end_time.required' => 'End time is required',
                'end_time.date_format' => 'End time must be in HH:MM format',
                'end_time.after' => 'End time must be after start time',
            ]);

            Log::info('Validation passed', ['validated_data' => $validatedData]);

            // Get the doctor
            $doctor = Doctor::findOrFail($request->doctor_id);
            Log::info('Doctor found', ['doctor' => $doctor->toArray()]);

            // Check for conflicting schedules
            $existingSchedule = Schedule::where('doctor_id', $request->doctor_id)
                ->where('day_of_week', $request->day_of_week)
                ->where(function($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>', $request->start_time);
                    })->orWhere(function($q) use ($request) {
                        $q->where('start_time', '<', $request->end_time)
                          ->where('end_time', '>=', $request->end_time);
                    })->orWhere(function($q) use ($request) {
                        $q->where('start_time', '>=', $request->start_time)
                          ->where('end_time', '<=', $request->end_time);
                    });
                })
                ->first();

            if ($existingSchedule) {
                Log::warning('Schedule conflict detected', ['existing_schedule' => $existingSchedule->toArray()]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Doctor already has a conflicting schedule on ' . ucfirst($request->day_of_week) . ' at this time.');
            }

            // Prepare data for creation
            $scheduleData = [
                'doctor_id' => $request->doctor_id,
                'user_id' => $doctor->user_id,
                'day_of_week' => $request->day_of_week,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_available' => $request->has('is_available') ? true : false,
            ];

            Log::info('Creating schedule with data', ['schedule_data' => $scheduleData]);

            // Create the schedule
            DB::beginTransaction();
            
            $schedule = Schedule::create($scheduleData);
            
            DB::commit();

            Log::info('Schedule created successfully', ['schedule' => $schedule->toArray()]);

            return redirect()->route('admin.schedules.index')
                ->with('success', 'Schedule created successfully for Dr. ' . $doctor->name . ' on ' . ucfirst($request->day_of_week));

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating schedule', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating schedule: ' . $e->getMessage());
        }
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['doctor', 'user', 'reservations']);
        return view('admin.schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $doctors = Doctor::all();
        return view('admin.schedules.edit', compact('schedule', 'doctors'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        Log::info('Schedule update method called', [
            'schedule_id' => $schedule->id,
            'request_data' => $request->all()
        ]);

        try {
            $validatedData = $request->validate([
                'doctor_id' => 'required|exists:doctors,id',
                'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'is_available' => 'nullable|boolean',
            ]);

            $doctor = Doctor::findOrFail($request->doctor_id);

            // Check for conflicting schedules (excluding current schedule)
            $existingSchedule = Schedule::where('doctor_id', $request->doctor_id)
                ->where('day_of_week', $request->day_of_week)
                ->where('id', '!=', $schedule->id)
                ->where(function($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>', $request->start_time);
                    })->orWhere(function($q) use ($request) {
                        $q->where('start_time', '<', $request->end_time)
                          ->where('end_time', '>=', $request->end_time);
                    })->orWhere(function($q) use ($request) {
                        $q->where('start_time', '>=', $request->start_time)
                          ->where('end_time', '<=', $request->end_time);
                    });
                })
                ->first();

            if ($existingSchedule) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Doctor already has a conflicting schedule on ' . ucfirst($request->day_of_week) . ' at this time.');
            }

            DB::beginTransaction();
            
            $schedule->update([
                'doctor_id' => $request->doctor_id,
                'user_id' => $doctor->user_id,
                'day_of_week' => $request->day_of_week,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'is_available' => $request->has('is_available') ? true : false,
            ]);
            
            DB::commit();

            Log::info('Schedule updated successfully', ['schedule' => $schedule->toArray()]);

            return redirect()->route('admin.schedules.index')
                ->with('success', 'Schedule updated successfully for Dr. ' . $doctor->name . ' on ' . ucfirst($request->day_of_week));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating schedule', [
                'error' => $e->getMessage(),
                'schedule_id' => $schedule->id,
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating schedule: ' . $e->getMessage());
        }
    }

    public function destroy(Schedule $schedule)
    {
        try {
            $doctorName = $schedule->doctor->name ?? 'Unknown Doctor';
            $dayOfWeek = ucfirst($schedule->day_of_week);
            
            $schedule->delete();
            
            Log::info('Schedule deleted successfully', ['schedule_id' => $schedule->id]);
            
            return redirect()->route('admin.schedules.index')
                ->with('success', 'Schedule for Dr. ' . $doctorName . ' on ' . $dayOfWeek . ' deleted successfully');
                
        } catch (\Exception $e) {
            Log::error('Error deleting schedule', [
                'error' => $e->getMessage(),
                'schedule_id' => $schedule->id
            ]);
            
            return redirect()->back()
                ->with('error', 'Error deleting schedule: ' . $e->getMessage());
        }
    }
}
