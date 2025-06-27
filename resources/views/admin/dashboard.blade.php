@extends('layouts.admin')

@section('admin-content')
<!-- Header Section -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 mb-1">Admin Dashboard</h1>
        <p class="text-muted">Welcome back! Here's what's happening with your clinic today.</p>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-muted me-2">Last updated:</span>
        <span class="badge bg-primary">{{ now()->format('M d, Y H:i') }}</span>
    </div>
</div>

<!-- Stats Cards Row -->
<div class="row g-4 mb-5">
    <!-- Doctors Card -->
    <div class="col-md-6 col-xl-3">
        <div class="card modern-card h-100 shadow-sm border-0 stats-card-doctors">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stats-icon-wrapper bg-primary bg-gradient">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="text-end">
                        <div class="stats-trend text-success">
                            <i class="fas fa-arrow-up"></i>
                            <small>+5%</small>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <h6 class="text-uppercase text-muted mb-2 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Doctors</h6>
                    <h2 class="mb-0 fw-bold text-dark">{{ $stats['doctors'] }}</h2>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-primary btn-sm px-3 rounded-pill">
                        <i class="fas fa-eye me-1"></i>View Details
                    </a>
                    <div class="progress-circle-sm" data-percentage="75">
                        <div class="progress-circle-inner">75%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Patients Card -->
    <div class="col-md-6 col-xl-3">
        <div class="card modern-card h-100 shadow-sm border-0 stats-card-patients">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stats-icon-wrapper bg-success bg-gradient">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="text-end">
                        <div class="stats-trend text-success">
                            <i class="fas fa-arrow-up"></i>
                            <small>+12%</small>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <h6 class="text-uppercase text-muted mb-2 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Patients</h6>
                    <h2 class="mb-0 fw-bold text-dark">{{ $stats['patients'] }}</h2>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-success btn-sm px-3 rounded-pill">
                        <i class="fas fa-eye me-1"></i>View Details
                    </a>
                    <div class="progress-circle-sm" data-percentage="85">
                        <div class="progress-circle-inner">85%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedules Card -->
    <div class="col-md-6 col-xl-3">
        <div class="card modern-card h-100 shadow-sm border-0 stats-card-schedules">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stats-icon-wrapper bg-info bg-gradient">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="text-end">
                        <div class="stats-trend text-info">
                            <i class="fas fa-minus"></i>
                            <small>0%</small>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <h6 class="text-uppercase text-muted mb-2 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Schedules</h6>
                    <h2 class="mb-0 fw-bold text-dark">{{ $stats['schedules'] }}</h2>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-info btn-sm px-3 rounded-pill">
                        <i class="fas fa-eye me-1"></i>View Details
                    </a>
                    <div class="progress-circle-sm" data-percentage="60">
                        <div class="progress-circle-inner">60%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservations Card -->
    <div class="col-md-6 col-xl-3">
        <div class="card modern-card h-100 shadow-sm border-0 stats-card-reservations">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stats-icon-wrapper bg-warning bg-gradient">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="text-end">
                        <div class="stats-trend text-success">
                            <i class="fas fa-arrow-up"></i>
                            <small>+8%</small>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <h6 class="text-uppercase text-muted mb-2 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Reservations</h6>
                    <h2 class="mb-0 fw-bold text-dark">{{ $stats['reservations'] }}</h2>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-warning btn-sm px-3 rounded-pill">
                        <i class="fas fa-eye me-1"></i>View Details
                    </a>
                    <div class="progress-circle-sm" data-percentage="90">
                        <div class="progress-circle-inner">90%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats Row -->
<div class="row g-4 mb-5">
    <!-- Medical Records Card -->
    <div class="col-md-6">
        <div class="card modern-card h-100 shadow-sm border-0 stats-card-medical">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="stats-icon-wrapper bg-danger bg-gradient">
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <div class="text-end">
                        <div class="stats-trend text-success">
                            <i class="fas fa-arrow-up"></i>
                            <small>+15%</small>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <h6 class="text-uppercase text-muted mb-2 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Medical Records</h6>
                    <h2 class="mb-0 fw-bold text-dark">{{ $stats['medical_records'] }}</h2>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('admin.medical-records.index') }}" class="btn btn-danger btn-sm px-3 rounded-pill">
                        <i class="fas fa-eye me-1"></i>View Details
                    </a>
                    <div class="progress-circle-sm" data-percentage="70">
                        <div class="progress-circle-inner">70%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="col-md-6">
        <div class="card modern-card h-100 shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="stats-icon-wrapper bg-secondary bg-gradient me-3">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">Quick Actions</h5>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('admin.doctors.create') }}" class="btn btn-outline-primary w-100 rounded-pill">
                            <i class="fas fa-plus me-1"></i>Add Doctor
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.patients.create') }}" class="btn btn-outline-success w-100 rounded-pill">
                            <i class="fas fa-plus me-1"></i>Add Patient
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.schedules.create') }}" class="btn btn-outline-info w-100 rounded-pill">
                            <i class="fas fa-plus me-1"></i>Add Schedule
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Card Styles */
.modern-card {
    border-radius: 15px;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    overflow: hidden;
    position: relative;
}

.modern-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.modern-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #007bff, #6f42c1);
}

.stats-card-doctors::before { background: linear-gradient(90deg, #007bff, #0056b3); }
.stats-card-patients::before { background: linear-gradient(90deg, #28a745, #1e7e34); }
.stats-card-schedules::before { background: linear-gradient(90deg, #17a2b8, #117a8b); }
.stats-card-reservations::before { background: linear-gradient(90deg, #ffc107, #e0a800); }
.stats-card-medical::before { background: linear-gradient(90deg, #dc3545, #c82333); }

/* Icon Wrapper */
.stats-icon-wrapper {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Stats Trend */
.stats-trend {
    font-size: 0.8rem;
    font-weight: 600;
}

/* Progress Circle */
.progress-circle-sm {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: conic-gradient(#007bff 0deg, #007bff calc(var(--percentage, 0) * 3.6deg), #e9ecef calc(var(--percentage, 0) * 3.6deg));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: bold;
    color: #007bff;
    position: relative;
}

.progress-circle-inner {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Button Enhancements */
.btn.rounded-pill {
    border-radius: 25px !important;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn.rounded-pill:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modern-card {
        margin-bottom: 1rem;
    }
    
    .stats-icon-wrapper {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }
    
    .progress-circle-sm {
        width: 35px;
        height: 35px;
    }
    
    .progress-circle-inner {
        width: 25px;
        height: 25px;
        font-size: 0.6rem;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-card {
    animation: fadeInUp 0.6s ease forwards;
}

.modern-card:nth-child(2) { animation-delay: 0.1s; }
.modern-card:nth-child(3) { animation-delay: 0.2s; }
.modern-card:nth-child(4) { animation-delay: 0.3s; }
</style>

<script>
// Initialize progress circles
document.addEventListener('DOMContentLoaded', function() {
    const progressCircles = document.querySelectorAll('.progress-circle-sm');
    progressCircles.forEach(circle => {
        const percentage = circle.getAttribute('data-percentage');
        circle.style.setProperty('--percentage', percentage);
    });
});
</script>
@endsection