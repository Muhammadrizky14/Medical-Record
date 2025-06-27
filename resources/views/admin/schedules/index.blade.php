@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Schedules Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Schedule
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
                        <th>Doctor</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                    <tr>
                        <td>{{ $loop->iteration + ($schedules->currentPage() - 1) * $schedules->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded-circle bg-primary text-white">
                                        {{ substr($schedule->doctor->name ?? 'N/A', 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $schedule->doctor->name ?? 'No Doctor' }}</div>
                                    <small class="text-muted">{{ $schedule->doctor->specialization ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($schedule->day_of_week) }}</span>
                        </td>
                        <td>
                            <div>
                                <i class="fas fa-clock text-muted"></i>
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            </div>
                        </td>
                        <td>
                            @if($schedule->is_available)
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">Not Available</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.schedules.show', $schedule) }}" 
                                   class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.schedules.edit', $schedule) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.schedules.destroy', $schedule) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this schedule?')">
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
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-calendar fa-3x mb-3"></i>
                                <p>No schedules found. <a href="{{ route('admin.schedules.create') }}">Add the first schedule</a></p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($schedules->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $schedules->links() }}
        </div>
        @endif
    </div>
</div>

<style>
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
