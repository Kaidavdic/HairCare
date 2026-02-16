<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'salon_id',
        'name',
        'description',
        'duration_minutes',
        'price',
        'is_promoted',
        'discount_price',
        'is_active',
        'average_rating',
        'reviews_count',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

