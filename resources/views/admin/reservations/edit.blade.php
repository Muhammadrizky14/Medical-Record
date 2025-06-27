@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Reservation</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reservations
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Reservation Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" 
                                    id="patient_id" name="patient_id" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" 
                                            {{ (old('patient_id') ?? $reservation->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }} - {{ \Carbon\Carbon::parse($patient->birth_date)->age }} years ({{ $patient->phone }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="doctor_id" class="form-label">Doctor <span class="text-danger">*</span></label>
                            <select class="form-select @error('doctor_id') is-invalid @enderror" 
                                    id="doctor_id" name="doctor_id" required>
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" 
                                            {{ (old('doctor_id') ?? $reservation->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }} - {{ $doctor->specialization }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="reservation_date" class="form-label">Reservation Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('reservation_date') is-invalid @enderror" 
                                   id="reservation_date" name="reservation_date" 
                                   value="{{ old('reservation_date') ?? $reservation->reservation_date->format('Y-m-d') }}" 
                                   required>
                            @error('reservation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="reservation_time" class="form-label">Reservation Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('reservation_time') is-invalid @enderror" 
                                   id="reservation_time" name="reservation_time" 
                                   value="{{ old('reservation_time') ?? $reservation->reservation_time->format('H:i') }}" required>
                            @error('reservation_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="pending" {{ (old('status') ?? $reservation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ (old('status') ?? $reservation->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ (old('status') ?? $reservation->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ (old('status') ?? $reservation->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="schedule_id" class="form-label">Schedule (Optional)</label>
                            <select class="form-select @error('schedule_id') is-invalid @enderror" 
                                    id="schedule_id" name="schedule_id">
                                <option value="">No specific schedule</option>
                                @foreach($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" 
                                            {{ (old('schedule_id') ?? $reservation->schedule_id) == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->doctor->name ?? 'No Doctor' }} - {{ ucfirst($schedule->day_of_week) }} 
                                        ({{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('schedule_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" 
                                  placeholder="Additional notes about the reservation">{{ old('notes') ?? $reservation->notes }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Reservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Current Details</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Patient:</strong><br>
                    <span class="text-muted">{{ $reservation->patient->name ?? 'No Patient' }}</span>
                </div>
                <div class="mb-2">
                    <strong>Doctor:</strong><br>
                    <span class="text-muted">{{ $reservation->doctor->name ?? 'No Doctor' }}</span>
                </div>
                <div class="mb-2">
                    <strong>Date & Time:</strong><br>
                    <span class="text-muted">{{ $reservation->reservation_date->format('M d, Y') }} at {{ $reservation->reservation_time->format('H:i') }}</span>
                </div>
                <div class="mb-2">
                    <strong>Status:</strong><br>
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'confirmed' => 'success',
                            'completed' => 'info',
                            'cancelled' => 'danger'
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$reservation->status] ?? 'secondary' }}">
                        {{ ucfirst($reservation->status) }}
                    </span>
                </div>
                
                <div class="mt-3">
                    <strong>Created:</strong><br>
                    <span class="text-muted">{{ $reservation->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="mt-2">
                    <strong>Last Updated:</strong><br>
                    <span class="text-muted">{{ $reservation->updated_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
