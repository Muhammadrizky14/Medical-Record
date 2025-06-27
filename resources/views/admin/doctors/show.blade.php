@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Doctor Details: {{ $doctor->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Full Name</label>
                            <p class="fw-bold">{{ $doctor->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Email Address</label>
                            <p>{{ $doctor->email }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Specialization</label>
                            <p><span class="badge bg-info fs-6">{{ $doctor->specialization }}</span></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone Number</label>
                            <p>{{ $doctor->phone }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Address</label>
                            <p>{{ $doctor->address }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Member Since</label>
                            <p>{{ $doctor->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="display-6 text-primary">{{ $doctor->schedules->count() }}</div>
                            <div class="text-muted">Schedules</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="display-6 text-success">{{ $doctor->reservations->count() }}</div>
                            <div class="text-muted">Reservations</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="display-6 text-info">{{ $doctor->medicalRecords->count() }}</div>
                            <div class="text-muted">Medical Records</div>
                        </div>
                    </div>
                </div>
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
                        Can access doctor dashboard
                    </div>
                    <div class="mb-2">
                        <strong>Role:</strong> {{ ucfirst($doctor->user->role) }}<br>
                        <strong>Last Updated:</strong> {{ $doctor->user->updated_at->diffForHumans() }}
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>No Account</strong><br>
                        Cannot access system
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Doctor
                    </a>
                    <a href="{{ route('admin.schedules.create') }}?doctor_id={{ $doctor->id }}" class="btn btn-info">
                        <i class="fas fa-calendar-plus"></i> Add Schedule
                    </a>
                    <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this doctor?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> Delete Doctor
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
