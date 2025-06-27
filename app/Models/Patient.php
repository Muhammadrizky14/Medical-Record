<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birth_date',
        'gender',
        'phone',
        'address',
        'medical_history',
        'doctor_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    // Accessor untuk menghitung umur dengan benar
    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return 0;
        }
        
        return Carbon::parse($this->birth_date)->age;
    }

    // Accessor untuk format umur yang lebih baik
    public function getFormattedAgeAttribute()
    {
        $age = $this->age;
        
        if ($age == 0) {
            // Jika umur 0, hitung dalam bulan
            $months = Carbon::parse($this->birth_date)->diffInMonths(Carbon::now());
            if ($months == 0) {
                $days = Carbon::parse($this->birth_date)->diffInDays(Carbon::now());
                return $days . ' hari';
            }
            return $months . ' bulan';
        }
        
        return $age . ' tahun';
    }
}
