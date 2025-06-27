@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Doctor: {{ $doctor->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Doctors
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Doctor Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $doctor->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $doctor->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="specialization" class="form-label">Specialization <span class="text-danger">*</span></label>
                            <select class="form-select @error('specialization') is-invalid @enderror" 
                                    id="specialization" name="specialization" required>
                                <option value="">Select Specialization</option>
                                <option value="General Medicine" {{ old('specialization', $doctor->specialization) == 'General Medicine' ? 'selected' : '' }}>General Medicine</option>
                                <option value="Cardiology" {{ old('specialization', $doctor->specialization) == 'Cardiology' ? 'selected' : '' }}>Cardiology</option>
                                <option value="Dermatology" {{ old('specialization', $doctor->specialization) == 'Dermatology' ? 'selected' : '' }}>Dermatology</option>
                                <option value="Neurology" {{ old('specialization', $doctor->specialization) == 'Neurology' ? 'selected' : '' }}>Neurology</option>
                                <option value="Orthopedics" {{ old('specialization', $doctor->specialization) == 'Orthopedics' ? 'selected' : '' }}>Orthopedics</option>
                                <option value="Pediatrics" {{ old('specialization', $doctor->specialization) == 'Pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                                <option value="Psychiatry" {{ old('specialization', $doctor->specialization) == 'Psychiatry' ? 'selected' : '' }}>Psychiatry</option>
                                <option value="Radiology" {{ old('specialization', $doctor->specialization) == 'Radiology' ? 'selected' : '' }}>Radiology</option>
                            </select>
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $doctor->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" required>{{ old('address', $doctor->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Doctor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Account Status</h6>
            </div>
            <div class="card-body">
                @if($doctor->user)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <strong>Active Account</strong><br>
                        This doctor has an active user account and can log in to the system.
                    </div>
                    <div class="mb-2">
                        <strong>Last Login:</strong><br>
                        <span class="text-muted">{{ $doctor->user->updated_at->diffForHumans() }}</span>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>No Account</strong><br>
                        This doctor doesn't have a user account yet.
                    </div>
                @endif
                
                <div class="mt-3">
                    <strong>Created:</strong><br>
                    <span class="text-muted">{{ $doctor->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
