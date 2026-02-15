<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'content',
        'read_at',
        'is_visible',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    protected $casts = [
        'is_visible' => 'boolean',
        'read_at' => 'datetime',
    ];
}
