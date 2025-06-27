@extends('layouts.doctor')

@section('doctor-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Dokter</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <span class="badge bg-primary fs-6">{{ Auth::user()->specialization ?? 'General Medicine' }}</span>
        </div>
    </div>
</div>

<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="alert alert-info border-left-primary">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-md fa-2x text-primary me-3"></i>
                <div>
                    <h5 class="mb-1">Selamat datang, Dr. {{ Auth::user()->name }}!</h5>
                    <p class="mb-0">Hari ini adalah {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card stats-card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-75 small">Total Pasien</div>
                        <div class="display-4 font-weight-bold">{{ $stats['patients'] }}</div>
                        <div class="text-white-50 small">
                            <i class="fas fa-users"></i> Pasien terdaftar
                        </div>
                    </div>
                    <div class="text-white-25">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('doctor.patients.index') }}" class="text-white text-decoration-none">
                    <small>Lihat semua pasien <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card stats-card bg-gradient-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-75 small">Total Reservasi</div>
                        <div class="display-4 font-weight-bold">{{ $stats['reservations'] }}</div>
                        <div class="text-white-50 small">
                            <i class="fas fa-calendar-check"></i> Janji temu
                        </div>
                    </div>
                    <div class="text-white-25">
                        <i class="fas fa-calendar-check fa-3x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('doctor.reservations.index') }}" class="text-white text-decoration-none">
                    <small>Lihat semua reservasi <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card stats-card bg-gradient-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-75 small">Rekam Medis</div>
                        <div class="display-4 font-weight-bold">{{ $stats['medical_records'] }}</div>
                        <div class="text-white-50 small">
                            <i class="fas fa-file-medical"></i> Total rekam medis
                        </div>
                    </div>
                    <div class="text-white-25">
                        <i class="fas fa-file-medical fa-3x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('doctor.medical-records.index') }}" class="text-white text-decoration-none">
                    <small>Lihat semua rekam medis <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt text-warning"></i> Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('doctor.patients.create') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                            <div>Tambah Pasien Baru</div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('doctor.medical-records.create') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-file-medical-alt fa-2x mb-2"></i>
                            <div>Buat Rekam Medis</div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('doctor.patients.index') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-search fa-2x mb-2"></i>
                            <div>Cari Pasien</div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('doctor.reservations.index') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                            <div>Lihat Jadwal</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-users text-primary"></i> Pasien Terbaru
                </h6>
            </div>
            <div class="card-body">
                @if(isset($recentPatients) && $recentPatients->count() > 0)
                    @foreach($recentPatients as $patient)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm me-3">
                            <div class="avatar-title rounded-circle {{ $patient->gender == 'female' ? 'bg-pink' : 'bg-blue' }} text-white">
                                {{ substr($patient->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $patient->name }}</div>
                            <small class="text-muted">{{ $patient->formatted_age }} • {{ $patient->created_at->diffForHumans() }}</small>
                        </div>
                        <a href="{{ route('doctor.patients.show', $patient) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <p>Belum ada pasien terbaru</p>
                        <a href="{{ route('doctor.patients.create') }}" class="btn btn-primary btn-sm">
                            Tambah Pasien Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-file-medical text-success"></i> Rekam Medis Terbaru
                </h6>
            </div>
            <div class="card-body">
                @if(isset($recentRecords) && $recentRecords->count() > 0)
                    @foreach($recentRecords as $record)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm me-3">
                            <div class="avatar-title rounded-circle bg-success text-white">
                                <i class="fas fa-file-medical"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $record->patient->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $record->visit_date->format('d M Y') }} • {{ Str::limit($record->diagnosis, 30) }}</small>
                        </div>
                        <a href="{{ route('doctor.medical-records.show', $record) }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-file-medical fa-2x mb-2"></i>
                        <p>Belum ada rekam medis</p>
                        <a href="{{ route('doctor.medical-records.create') }}" class="btn btn-success btn-sm">
                            Buat Rekam Medis Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}
.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #1e7e34);
}
.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #117a8b);
}
.bg-pink { background-color: #e91e63 !important; }
.bg-blue { background-color: #2196f3 !important; }
.border-left-primary {
    border-left: 4px solid #007bff !important;
}
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
.stats-card {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: transform 0.2s;
}
.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>
@endsection
