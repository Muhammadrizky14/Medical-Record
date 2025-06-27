@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Schedule</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Schedules
        </a>
    </div>
</div>

{{-- Debug: Show any errors --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <h6>Please fix the following errors:</h6>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Debug: Show success/error messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Schedule Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.schedules.store') }}" method="POST" id="scheduleForm">
                    @csrf
                    
                    <div class="row mb-3">
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
                        <div class="col-md-6">
                            <label for="day_of_week" class="form-label">Day of Week <span class="text-danger">*</span></label>
                            <select class="form-select @error('day_of_week') is-invalid @enderror" 
                                    id="day_of_week" name="day_of_week" required>
                                <option value="">Select Day</option>
                                <option value="monday" {{ old('day_of_week') == 'monday' ? 'selected' : '' }}>Monday</option>
                                <option value="tuesday" {{ old('day_of_week') == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                                <option value="wednesday" {{ old('day_of_week') == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                                <option value="thursday" {{ old('day_of_week') == 'thursday' ? 'selected' : '' }}>Thursday</option>
                                <option value="friday" {{ old('day_of_week') == 'friday' ? 'selected' : '' }}>Friday</option>
                                <option value="saturday" {{ old('day_of_week') == 'saturday' ? 'selected' : '' }}>Saturday</option>
                                <option value="sunday" {{ old('day_of_week') == 'sunday' ? 'selected' : '' }}>Sunday</option>
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
                                   id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1"
                                   {{ old('is_available') ? 'checked' : 'checked' }}>
                            <label class="form-check-label" for="is_available">
                                Available for appointments
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="spinner-border spinner-border-sm d-none" id="loadingSpinner"></span>
                            <i class="fas fa-save" id="saveIcon"></i> 
                            <span id="btnText">Create Schedule</span>
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
                    <strong>Note:</strong> Creating a schedule will allow patients to book appointments with the selected doctor on the specified day and time.
                </div>
                
                <h6>Required Fields:</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success"></i> Doctor</li>
                    <li><i class="fas fa-check text-success"></i> Day of Week</li>
                    <li><i class="fas fa-check text-success"></i> Start Time</li>
                    <li><i class="fas fa-check text-success"></i> End Time</li>
                </ul>

                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Important:</strong> End time must be after start time.
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Available Doctors</h6>
            </div>
            <div class="card-body">
                @if($doctors->count() > 0)
                    <p class="text-success">
                        <i class="fas fa-check-circle"></i>
                        {{ $doctors->count() }} doctors available
                    </p>
                    <small class="text-muted">
                        @foreach($doctors->take(3) as $doctor)
                            • {{ $doctor->name }} (ID: {{ $doctor->id }})<br>
                        @endforeach
                        @if($doctors->count() > 3)
                            ... and {{ $doctors->count() - 3 }} more
                        @endif
                    </small>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>No doctors found!</strong><br>
                        Please <a href="{{ route('admin.doctors.create') }}">add doctors</a> first.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('scheduleForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const saveIcon = document.getElementById('saveIcon');
    const btnText = document.getElementById('btnText');
    const doctorSelect = document.getElementById('doctor_id');

    // Form submission handling
    form.addEventListener('submit', function(e) {
        
        // Show loading state
        submitBtn.disabled = true;
        loadingSpinner.classList.remove('d-none');
        saveIcon.classList.add('d-none');
        btnText.textContent = 'Creating...';

        // Validate times
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;
        
        if (startTime && endTime && endTime <= startTime) {
            e.preventDefault();
            alert('End time must be after start time!');
            
            // Reset button state
            submitBtn.disabled = false;
            loadingSpinner.classList.add('d-none');
            saveIcon.classList.remove('d-none');
            btnText.textContent = 'Create Schedule';
            return false;
        }
    });

    // Reset button state if there are validation errors
    @if ($errors->any())
        submitBtn.disabled = false;
        loadingSpinner.classList.add('d-none');
        saveIcon.classList.remove('d-none');
        btnText.textContent = 'Create Schedule';
    @endif
});
</script>
@endsection
