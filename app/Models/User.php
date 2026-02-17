<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_SALON_OWNER = 'salon_owner';
    public const ROLE_CLIENT = 'client';

    protected $appends = ['profile_picture_url'];

    public function getProfilePictureUrlAttribute()
    {
        if (!$this->profile_picture) {
            // Return default avatar or null
             return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
        }

        if (\Illuminate\Support\Str::startsWith($this->profile_picture, 'http')) {
            return $this->profile_picture;
        }

        return asset('storage/' . $this->profile_picture);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'role',
        'status',
        'password_history',
        'interests',
        'password',
        'average_rating',
        'reviews_count',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_history' => 'array',
            'interests' => 'array',
        ];
    }

    public function salon()
    {
        return $this->hasOne(Salon::class, 'owner_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'client_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSalonOwner(): bool
    {
        return $this->role === self::ROLE_SALON_OWNER;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotificationsCount()
    {
        return $this->notifications()
            ->whereNull('read_at')
            ->where('is_visible', true)
            ->count();
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function unreadMessagesCount()
    {
        return $this->receivedMessages()
            ->whereNull('read_at')
            ->count();
    }
}
