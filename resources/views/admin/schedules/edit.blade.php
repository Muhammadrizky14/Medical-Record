@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Schedule</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Schedules
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Schedule Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="doctor_id" class="form-label">Doctor <span class="text-danger">*</span></label>
                            <select class="form-select @error('doctor_id') is-invalid @enderror" 
                                    id="doctor_id" name="doctor_id" required>
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" 
                                            {{ (old('doctor_id') ?? $schedule->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }} - {{ $doctor->specialization }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="day_of_week" class="form-label">Day of Week <span class="text-danger">*</span></label>
                            <select class="form-select @error('day_of_week') is-invalid @enderror" 
                                    id="day_of_week" name="day_of_week" required>
                                <option value="">Select Day</option>
                                <option value="monday" {{ (old('day_of_week') ?? $schedule->day_of_week) == 'monday' ? 'selected' : '' }}>Monday</option>
                                <option value="tuesday" {{ (old('day_of_week') ?? $schedule->day_of_week) == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                                <option value="wednesday" {{ (old('day_of_week') ?? $schedule->day_of_week) == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                                <option value="thursday" {{ (old('day_of_week') ?? $schedule->day_of_week) == 'thursday' ? 'selected' : '' }}>Thursday</option>
                                <option value="friday" {{ (old('day_of_week') ?? $schedule->day_of_week) == 'friday' ? 'selected' : '' }}>Friday</option>
                                <option value="saturday" {{ (old('day_of_week') ?? $schedule->day_of_week) == 'saturday' ? 'selected' : '' }}>Saturday</option>
                                <option value="sunday" {{ (old('day_of_week') ?? $schedule->day_of_week) == 'sunday' ? 'selected' : '' }}>Sunday</option>
                            </select>
                            @error('day_of_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" name="start_time" 
                                   value="{{ old('start_time') ?? $schedule->start_time->format('H:i') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" 
                                   value="{{ old('end_time') ?? $schedule->end_time->format('H:i') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_available" name="is_available" 
                                   {{ (old('is_available') ?? $schedule->is_available) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">
                                Available for appointments
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Schedule Details</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Current Doctor:</strong><br>
                    <span class="text-muted">{{ $schedule->doctor->name ?? 'No Doctor' }}</span>
                </div>
                <div class="mb-2">
                    <strong>Current Day:</strong><br>
                    <span class="text-muted">{{ ucfirst($schedule->day_of_week) }}</span>
                </div>
                <div class="mb-2">
                    <strong>Current Time:</strong><br>
                    <span class="text-muted">{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</span>
                </div>
                <div class="mb-2">
                    <strong>Status:</strong><br>
                    @if($schedule->is_available)
                        <span class="badge bg-success">Available</span>
                    @else
                        <span class="badge bg-secondary">Not Available</span>
                    @endif
                </div>
                
                <div class="mt-3">
                    <strong>Created:</strong><br>
                    <span class="text-muted">{{ $schedule->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validate time inputs
document.getElementById('end_time').addEventListener('change', function() {
    const startTime = document.getElementById('start_time').value;
    const endTime = this.value;
    
    if (startTime && endTime && endTime <= startTime) {
        alert('End time must be after start time!');
        this.value = '';
    }
});

document.getElementById('start_time').addEventListener('change', function() {
    const startTime = this.value;
    const endTime = document.getElementById('end_time').value;
    
    if (startTime && endTime && endTime <= startTime) {
        alert('End time must be after start time!');
        document.getElementById('end_time').value = '';
    }
});
</script>
@endsection
