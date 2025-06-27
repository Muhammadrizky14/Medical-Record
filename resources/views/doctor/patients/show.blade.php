@extends('layouts.doctor')

@section('doctor-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Patient: {{ $patient->name }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('doctor.patients.edit', $patient) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('doctor.patients.index') }}" class="btn btn-secondary">
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
                            <p class="fw-bold">{{ $patient->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Age</label>
                            <p>{{ \Carbon\Carbon::parse($patient->birth_date)->age }} years old</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Gender</label>
                            <p><span class="badge {{ $patient->gender == 'female' ? 'bg-pink' : 'bg-blue' }}">{{ ucfirst($patient->gender) }}</span></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone Number</label>
                            <p>{{ $patient->phone }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Address</label>
                            <p>{{ $patient->address }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Birth Date</label>
                            <p>{{ $patient->birth_date->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
                
                @if($patient->medical_history)
                <div class="mb-3">
                    <label class="form-label text-muted">Medical History</label>
                    <p>{{ $patient->medical_history }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Medical Records</h5>
            </div>
            <div class="card-body">
                @if($patient->medicalRecords->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Diagnosis</th>
                                    <th>Treatment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patient->medicalRecords->take(5) as $record)
                                <tr>
                                    <td>{{ $record->visit_date->format('M d, Y') }}</td>
                                    <td>{{ Str::limit($record->diagnosis, 50) }}</td>
                                    <td>{{ Str::limit($record->treatment, 50) }}</td>
                                    <td>
                                        <a href="{{ route('doctor.medical-records.show', $record) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No medical records found.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('doctor.medical-records.create') }}?patient_id={{ $patient->id }}" class="btn btn-success">
                        <i class="fas fa-file-medical"></i> Add Medical Record
                    </a>
                    <a href="{{ route('doctor.patients.edit', $patient) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Patient
                    </a>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="display-6 text-primary">{{ $patient->reservations->count() }}</div>
                        <div class="text-muted small">Reservations</div>
                    </div>
                    <div class="col-6">
                        <div class="display-6 text-success">{{ $patient->medicalRecords->count() }}</div>
                        <div class="text-muted small">Records</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-pink { background-color: #e91e63 !important; }
.bg-blue { background-color: #2196f3 !important; }
</style>
@endsection
