@extends('layouts.doctor')

@section('doctor-content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Medical Record</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('doctor.medical-records.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Records
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Medical Record Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('doctor.medical-records.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="patient_id" class="form-label">Patient <span class="text-danger">*</span></label>
                            <select class="form-select @error('patient_id') is-invalid @enderror" 
                                    id="patient_id" name="patient_id" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" 
                                            {{ (old('patient_id') == $patient->id || (isset($selectedPatient) && $selectedPatient->id == $patient->id)) ? 'selected' : '' }}>
                                        {{ $patient->name }} - {{ \Carbon\Carbon::parse($patient->birth_date)->age }} years
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="visit_date" class="form-label">Visit Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('visit_date') is-invalid @enderror" 
                                   id="visit_date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required>
                            @error('visit_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="symptoms" class="form-label">Symptoms <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('symptoms') is-invalid @enderror" 
                                  id="symptoms" name="symptoms" rows="3" required>{{ old('symptoms') }}</textarea>
                        @error('symptoms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="diagnosis" class="form-label">Diagnosis <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('diagnosis') is-invalid @enderror" 
                                  id="diagnosis" name="diagnosis" rows="3" required>{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="treatment" class="form-label">Treatment <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('treatment') is-invalid @enderror" 
                                  id="treatment" name="treatment" rows="3" required>{{ old('treatment') }}</textarea>
                        @error('treatment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="prescription" class="form-label">Prescription</label>
                        <textarea class="form-control @error('prescription') is-invalid @enderror" 
                                  id="prescription" name="prescription" rows="3">{{ old('prescription') }}</textarea>
                        @error('prescription')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('doctor.medical-records.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
