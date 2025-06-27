@extends('layouts.doctor')

@section('doctor-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">My Reservations</h1>
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
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Today's Appointments</h6>
                        <h3>{{ $todayReservations ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-day fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Pending</h6>
                        <h3>{{ $pendingReservations ?? 0 }}</h3>
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
                        <h3>{{ $confirmedReservations ?? 0 }}</h3>
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
                        <h3>{{ $completedReservations ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-double fa-2x"></i>
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
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded-circle bg-secondary text-white">
                                        {{ substr($reservation->patient->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $reservation->patient->name }}</div>
                                    <small class="text-muted">{{ $reservation->patient->phone }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $reservation->reservation_date->format('M d, Y') }}</td>
                        <td>{{ $reservation->reservation_time->format('H:i') }}</td>
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
                                @if($reservation->status == 'pending')
                                    <button class="btn btn-sm btn-success" onclick="updateStatus({{ $reservation->id }}, 'confirmed')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                @if($reservation->status == 'confirmed')
                                    <button class="btn btn-sm btn-info" onclick="updateStatus({{ $reservation->id }}, 'completed')">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                @endif
                                <a href="{{ route('doctor.reservations.show', $reservation) }}" 
                                   class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                <p>No reservations found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function updateStatus(reservationId, status) {
    if (confirm('Are you sure you want to update this reservation status?')) {
        // Here you would make an AJAX call to update the status
        fetch(`/doctor/reservations/${reservationId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endsection
