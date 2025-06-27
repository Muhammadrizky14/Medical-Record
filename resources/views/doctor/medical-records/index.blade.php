@extends('layouts.doctor')

@section('doctor-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Medical Records</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('doctor.medical-records.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Record
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Search Patient</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Patient name...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date From</label>
                        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date To</label>
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="{{ route('doctor.medical-records.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Visit Date</th>
                        <th>Diagnosis</th>
                        <th>Treatment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicalRecords as $record)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded-circle bg-primary text-white">
                                        {{ substr($record->patient->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $record->patient->name }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($record->patient->birth_date)->age }} years old</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $record->visit_date->format('M d, Y') }}</td>
                        <td>
                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $record->diagnosis }}">
                                {{ $record->diagnosis }}
                            </span>
                        </td>
                        <td>
                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $record->treatment }}">
                                {{ $record->treatment }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('doctor.medical-records.show', $record) }}" 
                                   class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('doctor.medical-records.edit', $record) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-success" title="Print" onclick="printRecord({{ $record->id }})">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-file-medical fa-3x mb-3"></i>
                                <p>No medical records found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($medicalRecords) && $medicalRecords->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $medicalRecords->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function printRecord(recordId) {
    // Open print page in new window
    const printWindow = window.open(`/doctor/medical-records/${recordId}/print`, '_blank', 'width=800,height=600');
    
    // Optional: Focus on the new window
    if (printWindow) {
        printWindow.focus();
    }
}
</script>
@endsection
