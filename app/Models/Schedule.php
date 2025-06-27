<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'user_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Accessor untuk format waktu yang aman
    public function getFormattedStartTimeAttribute()
    {
        if (is_string($this->start_time)) {
            return date('H:i', strtotime($this->start_time));
        }
        return $this->start_time instanceof Carbon ? $this->start_time->format('H:i') : $this->start_time;
    }

    public function getFormattedEndTimeAttribute()
    {
        if (is_string($this->end_time)) {
            return date('H:i', strtotime($this->end_time));
        }
        return $this->end_time instanceof Carbon ? $this->end_time->format('H:i') : $this->end_time;
    }

    // Accessor untuk format waktu yang lebih baik
    public function getFormattedTimeAttribute()
    {
        return $this->formatted_start_time . ' - ' . $this->formatted_end_time;
    }

    // Scope untuk jadwal yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    // Scope untuk jadwal berdasarkan hari
    public function scopeByDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }

    // Scope untuk jadwal berdasarkan dokter
    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }
}
