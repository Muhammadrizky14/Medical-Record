@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Reservations Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                Filter by Status
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?status=all">All</a></li>
                <li><a class="dropdown-item" href="?status=pending">Pending</a></li>
                <li><a class="dropdown-item" href="?status=confirmed">Confirmed</a></li>
                <li><a class="dropdown-item" href="?status=completed">Completed</a></li>
                <li><a class="dropdown-item" href="?status=cancelled">Cancelled</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Reservation
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Pending</h6>
                        <h3>{{ $reservations->where('status', 'pending')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Confirmed</h6>
                        <h3>{{ $reservations->where('status', 'confirmed')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Completed</h6>
                        <h3>{{ $reservations->where('status', 'completed')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-double fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Cancelled</h6>
                        <h3>{{ $reservations->where('status', 'cancelled')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
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
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
                        <td>{{ $loop->iteration + ($reservations->currentPage() - 1) * $reservations->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded-circle bg-secondary text-white">
                                        {{ substr($reservation->patient->name ?? 'N/A', 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $reservation->patient->name ?? 'No Patient' }}</div>
                                    <small class="text-muted">{{ $reservation->patient->phone ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded-circle bg-primary text-white">
                                        {{ substr($reservation->doctor->name ?? 'N/A', 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $reservation->doctor->name ?? 'No Doctor' }}</div>
                                    <small class="text-muted">{{ $reservation->doctor->specialization ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold">{{ $reservation->reservation_date->format('M d, Y') }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $reservation->reservation_time->format('H:i') }}
                                </small>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'success',
                                    'completed' => 'info',
                                    'cancelled' => 'danger'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$reservation->status] ?? 'secondary' }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td>
                            @if($reservation->notes)
                                <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $reservation->notes }}">
                                    {{ $reservation->notes }}
                                </span>
                            @else
                                <span class="text-muted">No notes</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.reservations.show', $reservation) }}" 
                                   class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.reservations.edit', $reservation) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.reservations.destroy', $reservation) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this reservation?')">
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
                                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                <p>No reservations found. <a href="{{ route('admin.reservations.create') }}">Add the first reservation</a></p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($reservations->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $reservations->links() }}
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
