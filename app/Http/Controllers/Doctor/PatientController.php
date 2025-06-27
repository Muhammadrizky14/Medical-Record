<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Reservation;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor) {
            // Create doctor record if it doesn't exist
            $doctor = Doctor::create([
                'name' => $user->name,
                'specialization' => $user->specialization ?? 'General Medicine',
                'phone' => $user->phone ?? '',
                'email' => $user->email,
                'address' => $user->address ?? '',
                'user_id' => $user->id,
            ]);
        }

        // Get patients directly assigned to this doctor OR patients with reservations with this doctor
        $patients = Patient::where(function($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id)
                  ->orWhereHas('reservations', function($q) use ($doctor) {
                      $q->where('doctor_id', $doctor->id);
                  });
        })->with(['medicalRecords' => function($query) {
            $query->orderBy('visit_date', 'desc');
        }])->distinct()->orderBy('created_at', 'desc')->paginate(10);

        return view('doctor.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('doctor.patients.create');
    }

    public function store(Request $request)
    {
        // Validasi input dengan aturan yang lebih ketat
        $request->validate([
            'name' => 'required|string|max:255|min:2',
            'birth_date' => [
                'required',
                'date',
                'before:today',
                'after:' . Carbon::now()->subYears(120)->format('Y-m-d')
            ],
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|max:20|min:10|unique:patients,phone|regex:/^[0-9]+$/',
            'address' => 'required|string|max:500|min:10',
            'medical_history' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Nama pasien wajib diisi',
            'name.min' => 'Nama pasien minimal 2 karakter',
            'birth_date.required' => 'Tanggal lahir wajib diisi',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini',
            'birth_date.after' => 'Umur tidak boleh lebih dari 120 tahun',
            'gender.required' => 'Jenis kelamin wajib dipilih',
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone.unique' => 'Nomor telepon sudah terdaftar',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka',
            'phone.min' => 'Nomor telepon minimal 10 digit',
            'address.required' => 'Alamat wajib diisi',
            'address.min' => 'Alamat minimal 10 karakter',
        ]);

        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor) {
            // Create doctor record if it doesn't exist
            $doctor = Doctor::create([
                'name' => $user->name,
                'specialization' => $user->specialization ?? 'General Medicine',
                'phone' => $user->phone ?? '',
                'email' => $user->email,
                'address' => $user->address ?? '',
                'user_id' => $user->id,
            ]);
        }

        try {
            // Validasi tambahan untuk tanggal lahir
            $birthDate = Carbon::parse($request->birth_date);
            $age = $birthDate->age;
            
            if ($age > 120) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Umur pasien tidak boleh lebih dari 120 tahun.');
            }

            // Create patient and assign to this doctor
            $patient = Patient::create([
                'name' => trim($request->name),
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => trim($request->address),
                'medical_history' => $request->medical_history ? trim($request->medical_history) : null,
                'doctor_id' => $doctor->id
            ]);

            return redirect()->route('doctor.patients.index')
                           ->with('success', 'Pasien "' . $patient->name . '" berhasil ditambahkan! Umur: ' . $patient->formatted_age);
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan saat menyimpan data pasien. Silakan coba lagi.');
        }
    }

    public function show(Patient $patient)
    {
        // Verify this doctor has access to this patient
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor) {
            abort(403, 'Data dokter tidak ditemukan.');
        }

        $hasAccess = $patient->doctor_id === $doctor->id || 
                     $patient->reservations()->where('doctor_id', $doctor->id)->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke data pasien ini.');
        }

        $patient->load(['reservations', 'medicalRecords' => function($query) {
            $query->orderBy('visit_date', 'desc');
        }]);
        
        return view('doctor.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        // Verify access
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor) {
            abort(403, 'Data dokter tidak ditemukan.');
        }

        $hasAccess = $patient->doctor_id === $doctor->id || 
                     $patient->reservations()->where('doctor_id', $doctor->id)->exists();
        
        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke data pasien ini.');
        }

        return view('doctor.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        // Validasi input dengan aturan yang lebih ketat
        $request->validate([
            'name' => 'required|string|max:255|min:2',
            'birth_date' => [
                'required',
                'date',
                'before:today',
                'after:' . Carbon::now()->subYears(120)->format('Y-m-d')
            ],
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|max:20|min:10|unique:patients,phone,' . $patient->id . '|regex:/^[0-9]+$/',
            'address' => 'required|string|max:500|min:10',
            'medical_history' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Nama pasien wajib diisi',
            'name.min' => 'Nama pasien minimal 2 karakter',
            'birth_date.required' => 'Tanggal lahir wajib diisi',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini',
            'birth_date.after' => 'Umur tidak boleh lebih dari 120 tahun',
            'gender.required' => 'Jenis kelamin wajib dipilih',
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone.unique' => 'Nomor telepon sudah terdaftar',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka',
            'phone.min' => 'Nomor telepon minimal 10 digit',
            'address.required' => 'Alamat wajib diisi',
            'address.min' => 'Alamat minimal 10 karakter',
        ]);

        // Verify access
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor) {
            abort(403, 'Data dokter tidak ditemukan.');
        }

        $hasAccess = $patient->doctor_id === $doctor->id || 
                     $patient->reservations()->where('doctor_id', $doctor->id)->exists();
        
        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke data pasien ini.');
        }

        try {
            // Validasi tambahan untuk tanggal lahir
            $birthDate = Carbon::parse($request->birth_date);
            $age = $birthDate->age;
            
            if ($age > 120) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Umur pasien tidak boleh lebih dari 120 tahun.');
            }

            $patient->update([
                'name' => trim($request->name),
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => trim($request->address),
                'medical_history' => $request->medical_history ? trim($request->medical_history) : null,
            ]);

            return redirect()->route('doctor.patients.index')
                           ->with('success', 'Data pasien "' . $patient->name . '" berhasil diperbarui! Umur: ' . $patient->formatted_age);
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan saat memperbarui data pasien. Silakan coba lagi.');
        }
    }

    public function destroy(Patient $patient)
    {
        // Verify access
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor) {
            abort(403, 'Data dokter tidak ditemukan.');
        }

        $hasAccess = $patient->doctor_id === $doctor->id || 
                     $patient->reservations()->where('doctor_id', $doctor->id)->exists();
        
        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke data pasien ini.');
        }

        try {
            $patientName = $patient->name;
            $patient->delete();

            return redirect()->route('doctor.patients.index')
                           ->with('success', 'Pasien "' . $patientName . '" berhasil dihapus!');
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat menghapus data pasien. Silakan coba lagi.');
        }
    }
}
