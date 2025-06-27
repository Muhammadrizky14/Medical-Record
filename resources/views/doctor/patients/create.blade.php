@extends('layouts.doctor')

@section('doctor-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Pasien Baru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('doctor.patients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pasien
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Pasien</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('doctor.patients.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required 
                                   placeholder="Masukkan nama lengkap pasien">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                   id="birth_date" name="birth_date" value="{{ old('birth_date') }}" 
                                   max="{{ date('Y-m-d') }}" 
                                   min="{{ date('Y-m-d', strtotime('-120 years')) }}"
                                   required>
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tanggal lahir tidak boleh di masa depan</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select @error('gender') is-invalid @enderror" 
                                    id="gender" name="gender" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required
                                   placeholder="Contoh: 081234567890"
                                   pattern="[0-9]{10,15}"
                                   title="Nomor telepon harus berupa angka 10-15 digit">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" required
                                  placeholder="Masukkan alamat lengkap pasien">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="medical_history" class="form-label">Riwayat Penyakit</label>
                        <textarea class="form-control @error('medical_history') is-invalid @enderror" 
                                  id="medical_history" name="medical_history" rows="4"
                                  placeholder="Masukkan riwayat penyakit pasien (opsional)">{{ old('medical_history') }}</textarea>
                        @error('medical_history')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Opsional: Riwayat penyakit, alergi, atau kondisi medis lainnya</div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('doctor.patients.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pasien
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Panduan Pengisian</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Tips:</strong> Pastikan data pasien yang dimasukkan akurat untuk memudahkan pelayanan medis.
                </div>
                
                <h6>Field Wajib:</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success"></i> Nama Lengkap</li>
                    <li><i class="fas fa-check text-success"></i> Tanggal Lahir</li>
                    <li><i class="fas fa-check text-success"></i> Jenis Kelamin</li>
                    <li><i class="fas fa-check text-success"></i> Nomor Telepon</li>
                    <li><i class="fas fa-check text-success"></i> Alamat</li>
                </ul>
                
                <h6 class="mt-3">Field Opsional:</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-minus text-muted"></i> Riwayat Penyakit</li>
                </ul>

                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Tanggal lahir harus valid dan tidak boleh di masa depan.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validasi tanggal lahir real-time
document.getElementById('birth_date').addEventListener('change', function() {
    const birthDate = new Date(this.value);
    const today = new Date();
    
    if (birthDate > today) {
        alert('Tanggal lahir tidak boleh di masa depan!');
        this.value = '';
        return;
    }
    
    // Hitung dan tampilkan umur
    const age = Math.floor((today - birthDate) / (365.25 * 24 * 60 * 60 * 1000));
    if (age > 120) {
        alert('Umur tidak boleh lebih dari 120 tahun!');
        this.value = '';
        return;
    }
    
    // Tampilkan preview umur
    const agePreview = document.getElementById('age-preview');
    if (agePreview) {
        agePreview.remove();
    }
    
    const preview = document.createElement('div');
    preview.id = 'age-preview';
    preview.className = 'form-text text-success';
    preview.innerHTML = `<i class="fas fa-info-circle"></i> Umur: ${age} tahun`;
    this.parentNode.appendChild(preview);
});
</script>
@endsection
