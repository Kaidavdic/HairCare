<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'location',
        'image_url',
        'description',
        'type',
        'status',
        'opening_hour',
        'closing_hour',
        'closed_days',
    ];

    protected static function boot()
    {
        parent::boot();

        // Clean up salon images from storage when salon is deleted
        static::deleting(function ($salon) {
            // Delete all image files from storage
            foreach ($salon->images as $image) {
                if ($image->image_url) {
                    $path = str_replace('/storage/', '', $image->image_url);
                    \Storage::disk('public')->delete($path);
                }
            }
        });
    }


    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images()
    {
        return $this->hasMany(SalonImage::class)->orderBy('order');
    }

    public function getAvailableSlots($date, $durationMinutes)
    {
        $openingHour = $this->opening_hour ?? 9;
        $closingHour = $this->closing_hour ?? 18;
        
        // Handle closed_days as JSON, with fallback
        $closedDays = $this->closed_days;
        if (is_string($closedDays)) {
            $closedDays = json_decode($closedDays, true);
        }
        if (!is_array($closedDays)) {
            $closedDays = [];
        }
        
        try {
            // Ensure date is valid
            try {
                $dateObj = \Carbon\Carbon::parse($date);
            } catch (\Exception $e) {
                return collect();
            }

            $dayOfWeek = $dateObj->dayOfWeek;

            // Check if salon is closed this day
            if (in_array($dayOfWeek, $closedDays)) {
                return collect();
            }

            // Get all appointments for this date
            $appointments = $this->appointments()
                ->whereDate('scheduled_at', $date)
                ->where('status', '!=', 'cancelled')
                ->where('status', '!=', 'rejected') // Also exclude rejected
                ->get();

            $slots = collect();
            $startTime = $dateObj->copy()
                ->setHour($openingHour)
                ->setMinute(0)
                ->setSecond(0);
            $endTime = $dateObj->copy()
                ->setHour($closingHour)
                ->setMinute(0)
                ->setSecond(0);

            $time = $startTime->copy();
            
            // Ensure duration is integer
            $durationMinutes = (int) $durationMinutes;
            if ($durationMinutes <= 0) {
                $durationMinutes = 30;
            }

            while ($time < $endTime) {
                $slotEnd = $time->copy()->addMinutes($durationMinutes);

                // Check if slot goes beyond closing time
                if ($slotEnd > $endTime) {
                    break;
                }

                // Check if slot is available
                $isAvailable = !$appointments->contains(function ($apt) use ($time, $slotEnd) {
                    $aptStart = \Carbon\Carbon::parse($apt->scheduled_at);
                    $aptEnd = \Carbon\Carbon::parse($apt->ends_at);
                    
                    // Logic: 
                    // Slot: [time, slotEnd)
                    // Apt:  [aptStart, aptEnd)
                    // Overlap if: time < aptEnd AND slotEnd > aptStart
                    
                    return $time < $aptEnd && $slotEnd > $aptStart;
                });

                $slots->push([
                    'time' => $time->format('H:i'),
                    'datetime' => $time->format('Y-m-d H:i:s'),
                    'available' => $isAvailable,
                ]);
                
                $time = $time->copy()->addMinutes(30);
            }

            return $slots;
        } catch (\Exception $e) {
            \Log::error('getAvailableSlots error: ' . $e->getMessage());
            return collect();
        }
    }
}
