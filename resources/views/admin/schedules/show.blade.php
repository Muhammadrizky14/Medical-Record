@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Schedule Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Schedule Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Doctor</label>
                            <p class="fw-bold">{{ $schedule->doctor->name ?? 'No Doctor' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Specialization</label>
                            <p><span class="badge bg-info fs-6">{{ $schedule->doctor->specialization ?? 'N/A' }}</span></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Day of Week</label>
                            <p><span class="badge bg-primary fs-6">{{ ucfirst($schedule->day_of_week) }}</span></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Time</label>
                            <p class="fw-bold">
                                <i class="fas fa-clock text-muted"></i>
                                {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <p>
                                @if($schedule->is_available)
                                    <span class="badge bg-success fs-6">Available</span>
                                @else
                                    <span class="badge bg-secondary fs-6">Not Available</span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Created</label>
                            <p>{{ $schedule->created_at->format('F d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($schedule->reservations->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Related Reservations</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedule->reservations->take(10) as $reservation)
                            <tr>
                                <td>{{ $reservation->patient->name ?? 'N/A' }}</td>
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
                                    <a href="{{ route('admin.reservations.show', $reservation) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <div class="display-6 text-primary">{{ $schedule->reservations->count() }}</div>
                        <div class="text-muted">Total Reservations</div>
                    </div>
                </div>
                
                @if($schedule->reservations->count() > 0)
                <div class="row text-center">
                    <div class="col-6">
                        <div class="h4 text-success">{{ $schedule->reservations->where('status', 'confirmed')->count() }}</div>
                        <div class="text-muted small">Confirmed</div>
                    </div>
                    <div class="col-6">
                        <div class="h4 text-warning">{{ $schedule->reservations->where('status', 'pending')->count() }}</div>
                        <div class="text-muted small">Pending</div>
                    </div>
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
                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Schedule
                    </a>
                    <a href="{{ route('admin.reservations.create') }}?schedule_id={{ $schedule->id }}" class="btn btn-info">
                        <i class="fas fa-calendar-plus"></i> Add Reservation
                    </a>
                    <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> Delete Schedule
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Doctor Information</h6>
            </div>
            <div class="card-body">
                @if($schedule->doctor)
                <div class="mb-2">
                    <strong>Name:</strong><br>
                    <span class="text-muted">{{ $schedule->doctor->name }}</span>
                </div>
                <div class="mb-2">
                    <strong>Email:</strong><br>
                    <span class="text-muted">{{ $schedule->doctor->email }}</span>
                </div>
                <div class="mb-2">
                    <strong>Phone:</strong><br>
                    <span class="text-muted">{{ $schedule->doctor->phone }}</span>
                </div>
                <div class="mb-2">
                    <strong>Specialization:</strong><br>
                    <span class="text-muted">{{ $schedule->doctor->specialization }}</span>
                </div>
                @else
                <p class="text-muted">No doctor information available</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
