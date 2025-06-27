<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'reservation_id',
        'diagnosis',
        'symptoms',
        'treatment',
        'prescription',
        'notes',
        'visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
