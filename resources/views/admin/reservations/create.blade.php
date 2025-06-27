@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Reservation</h1>
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
                <form action="{{ route('admin.reservations.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" 
                                    id="patient_id" name="patient_id" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
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
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
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
                                   id="reservation_date" name="reservation_date" value="{{ old('reservation_date') }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('reservation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="reservation_time" class="form-label">Reservation Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('reservation_time') is-invalid @enderror" 
                                   id="reservation_time" name="reservation_time" value="{{ old('reservation_time') }}" required>
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
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                                    <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->doctor->name ?? 'No Doctor' }} - {{ ucfirst($schedule->day_of_week) }} 
                                        ({{ $schedule->formatted_time }})
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
                                  placeholder="Additional notes about the reservation">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Reservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Information</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> Creating a reservation will schedule an appointment between the selected patient and doctor.
                </div>
                
                <h6>Required Fields:</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success"></i> Patient</li>
                    <li><i class="fas fa-check text-success"></i> Doctor</li>
                    <li><i class="fas fa-check text-success"></i> Reservation Date</li>
                    <li><i class="fas fa-check text-success"></i> Reservation Time</li>
                    <li><i class="fas fa-check text-success"></i> Status</li>
                </ul>

                <h6 class="mt-3">Optional Fields:</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-minus text-muted"></i> Schedule</li>
                    <li><i class="fas fa-minus text-muted"></i> Notes</li>
                </ul>

                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Important:</strong> Reservation date cannot be in the past.
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Status Guide</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <span class="badge bg-warning">Pending</span> - Waiting for confirmation
                </div>
                <div class="mb-2">
                    <span class="badge bg-success">Confirmed</span> - Appointment confirmed
                </div>
                <div class="mb-2">
                    <span class="badge bg-info">Completed</span> - Appointment finished
                </div>
                <div class="mb-2">
                    <span class="badge bg-danger">Cancelled</span> - Appointment cancelled
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validate reservation date
document.getElementById('reservation_date').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        alert('Reservation date cannot be in the past!');
        this.value = '';
    }
});

// Filter schedules based on selected doctor
document.getElementById('doctor_id').addEventListener('change', function() {
    const doctorId = this.value;
    const scheduleSelect = document.getElementById('schedule_id');
    
    // Reset schedule options
    Array.from(scheduleSelect.options).forEach(option => {
        if (option.value !== '') {
            option.style.display = 'none';
        }
    });
    
    if (doctorId) {
        // Show only schedules for selected doctor
        Array.from(scheduleSelect.options).forEach(option => {
            if (option.value !== '' && option.text.includes(document.querySelector(`#doctor_id option[value="${doctorId}"]`).text.split(' - ')[0])) {
                option.style.display = 'block';
            }
        });
    } else {
        // Show all schedules
        Array.from(scheduleSelect.options).forEach(option => {
            option.style.display = 'block';
        });
    }
    
    scheduleSelect.value = '';
});
</script>
@endsection
