@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Patients Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Patient
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Last Visit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td>{{ $loop->iteration + ($patients->currentPage() - 1) * $patients->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded-circle {{ $patient->gender == 'female' ? 'bg-pink' : 'bg-blue' }} text-white">
                                        {{ substr($patient->name, 0, 1) }}
                                    </div>
                                </div>
                                {{ $patient->name }}
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($patient->birth_date)->age }} years</td>
                        <td>
                            <span class="badge {{ $patient->gender == 'female' ? 'bg-pink' : 'bg-blue' }}">
                                {{ ucfirst($patient->gender) }}
                            </span>
                        </td>
                        <td>{{ $patient->phone }}</td>
                        <td>
                            @if($patient->medicalRecords->count() > 0)
                                {{ $patient->medicalRecords->first()->visit_date->format('M d, Y') }}
                            @else
                                <span class="text-muted">No visits</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.patients.show', $patient) }}" 
                                   class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.patients.edit', $patient) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.patients.destroy', $patient) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this patient?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <p>No patients found. <a href="{{ route('admin.patients.create') }}">Add the first patient</a></p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($patients->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $patients->links() }}
        </div>
        @endif
    </div>
</div>

<style>
.bg-pink { background-color: #e91e63 !important; }
.bg-blue { background-color: #2196f3 !important; }
.avatar-sm {
    width: 32px;
    height: 32px;
}
.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
}
</style>
@endsection
