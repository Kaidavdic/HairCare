<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalonImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'salon_id',
        'image_url',
        'alt_text',
        'order',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}
