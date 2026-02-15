<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'salon_id',
        'service_id',
        'client_id',
        'scheduled_at',
        'ends_at',
        'status',
        'note',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function serviceReview()
    {
        return $this->hasOne(Review::class)->where('type', 'service');
    }

    public function userReview()
    {
        return $this->hasOne(Review::class)->where('type', 'user');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeForSalon($query, int $salonId)
    {
        return $query->where('salon_id', $salonId);
    }

    public static function computeEndTime(Carbon $start, int $durationMinutes): Carbon
    {
        return $start->copy()->addMinutes($durationMinutes);
    }
}

