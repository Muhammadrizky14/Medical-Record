@extends('layouts.doctor')

@section('doctor-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Daftar Pasien Saya</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('doctor.patients.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Pasien Baru
        </a>
    </div>
</div>

@if($patients->count() > 0)
<div class="row mb-3">
    <div class="col-md-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Total Pasien:</strong> {{ $patients->total() }} pasien terdaftar
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Umur</th>
                        <th>Jenis Kelamin</th>
                        <th>Telepon</th>
                        <th>Kunjungan Terakhir</th>
                        <th>Aksi</th>
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
                                <div>
                                    <div class="fw-bold">{{ $patient->name }}</div>
                                    <small class="text-muted">
                                        Terdaftar: {{ $patient->created_at->format('d M Y') }} | 
                                        Lahir: {{ $patient->birth_date ? $patient->birth_date->format('d M Y') : 'Tidak diketahui' }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $patient->formatted_age }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $patient->gender == 'female' ? 'bg-pink' : 'bg-blue' }}">
                                {{ $patient->gender == 'female' ? 'Perempuan' : 'Laki-laki' }}
                            </span>
                        </td>
                        <td>
                            <a href="tel:{{ $patient->phone }}" class="text-decoration-none">
                                {{ $patient->phone }}
                            </a>
                        </td>
                        <td>
                            @if($patient->medicalRecords->count() > 0)
                                <span class="text-success">
                                    <i class="fas fa-calendar-check"></i>
                                    {{ $patient->medicalRecords->first()->visit_date->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-muted">
                                    <i class="fas fa-calendar-times"></i>
                                    Belum ada kunjungan
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('doctor.patients.show', $patient) }}" 
                                   class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('doctor.patients.edit', $patient) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('doctor.medical-records.create') }}?patient_id={{ $patient->id }}" 
                                   class="btn btn-sm btn-outline-success" title="Tambah Rekam Medis">
                                    <i class="fas fa-file-medical"></i>
                                </a>
                                <form action="{{ route('doctor.patients.destroy', $patient) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pasien {{ $patient->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                                <h5>Belum Ada Pasien</h5>
                                <p>Anda belum memiliki pasien yang terdaftar.</p>
                                <a href="{{ route('doctor.patients.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Pasien Pertama
                                </a>
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
