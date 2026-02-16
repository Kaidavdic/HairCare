<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'salon_id',
        'service_id',
        'client_id',
        'type',
        'reviewed_user_id',
        'rating', // Legacy, keep for now
        'service_rating',
        'salon_rating',
        'comment',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function reviewedUser()
    {
        return $this->belongsTo(User::class, 'reviewed_user_id');
    }
}

