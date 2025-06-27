@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Doctor</h1>
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
                <form action="{{ route('admin.doctors.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimum 8 characters</div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="specialization" class="form-label">Specialization <span class="text-danger">*</span></label>
                            <select class="form-select @error('specialization') is-invalid @enderror" 
                                    id="specialization" name="specialization" required>
                                <option value="">Select Specialization</option>
                                <option value="General Medicine" {{ old('specialization') == 'General Medicine' ? 'selected' : '' }}>General Medicine</option>
                                <option value="Cardiology" {{ old('specialization') == 'Cardiology' ? 'selected' : '' }}>Cardiology</option>
                                <option value="Dermatology" {{ old('specialization') == 'Dermatology' ? 'selected' : '' }}>Dermatology</option>
                                <option value="Neurology" {{ old('specialization') == 'Neurology' ? 'selected' : '' }}>Neurology</option>
                                <option value="Orthopedics" {{ old('specialization') == 'Orthopedics' ? 'selected' : '' }}>Orthopedics</option>
                                <option value="Pediatrics" {{ old('specialization') == 'Pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                                <option value="Psychiatry" {{ old('specialization') == 'Psychiatry' ? 'selected' : '' }}>Psychiatry</option>
                                <option value="Radiology" {{ old('specialization') == 'Radiology' ? 'selected' : '' }}>Radiology</option>
                            </select>
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Doctor
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
                    <strong>Note:</strong> Creating a doctor will automatically create a user account with doctor role privileges.
                </div>
                
                <h6>Required Fields:</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success"></i> Full Name</li>
                    <li><i class="fas fa-check text-success"></i> Email Address</li>
                    <li><i class="fas fa-check text-success"></i> Password</li>
                    <li><i class="fas fa-check text-success"></i> Specialization</li>
                    <li><i class="fas fa-check text-success"></i> Phone Number</li>
                    <li><i class="fas fa-check text-success"></i> Address</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
