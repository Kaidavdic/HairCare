<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Salon;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class SalonController extends Controller
{
    /**
     * Public list of approved salons with filters.
     */
    /**
     * Public list of approved salons (Redirects to home).
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('home');
    }

    /**
     * Salon details with services and reviews.
     */
    public function show(Salon $salon): View
    {
        $salon->load([
            'images',
            'services' => fn ($q) => $q->where('is_active', true)->orderBy('name'),
        ]);

        $reviews = $salon->reviews()
            ->with(['client', 'service'])
            ->where('type', 'service')
            ->latest()
            ->paginate(5);

        return view('salons.show', [
            'salon' => $salon,
            'reviews' => $reviews,
        ]);
    }

    /**
     * Create or update the salon for the logged-in owner.
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->saveSalon($request);
    }

    public function update(Request $request): RedirectResponse
    {
        return $this->saveSalon($request);
    }

    protected function saveSalon(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:male,female,unisex'],
            'opening_hour' => ['nullable', 'integer', 'min:0', 'max:23'],
            'closing_hour' => ['nullable', 'integer', 'min:0', 'max:23'],
            'closed_days' => ['nullable', 'array'],
        ]);

        // Handle closed_days as JSON
        if (isset($data['closed_days'])) {
            $data['closed_days'] = json_encode(array_map('intval', $data['closed_days']));
        } else {
            $data['closed_days'] = json_encode([]);
        }

        $salon = $user->salon;

        if (! $salon) {
            $salon = $user->salon()->create([
                'owner_id' => $user->id,
                'status' => 'pending',
                ...$data,
            ]);
        } else {
            $salon->fill($data);

            if ($salon->isDirty() && $salon->status === 'rejected') {
                // After changes, send back to admin for review.
                $salon->status = 'pending';
            }

            $salon->save();
        }

        return redirect()
            ->route('owner.salon.edit')
            ->with('status', 'Podaci o salonu su sačuvani. Trenutni status: ' . $salon->status . '.');
    }

    /**
     * Owner salon edit page.
     */
    public function edit(Request $request): View
    {
        $salon = $request->user()->salon;

        return view('owner.salon.edit', [
            'salon' => $salon,
        ]);
    }

    /**
     * Store appointment request for selected service.
     */
    public function storeAppointment(Request $request, Salon $salon, Service $service): RedirectResponse
    {
        $user = $request->user();

        // Only clients can book appointments
        if (! $user->isClient()) {
            abort(403);
        }

        $request->validate([
            'scheduled_at' => ['required', 'date_format:Y-m-d H:i:s', 'after:now'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $start = Carbon::parse($request->input('scheduled_at'));
        $end = Appointment::computeEndTime($start, $service->duration_minutes);

        // Check if within working hours
        $openingHour = $salon->opening_hour ?? 9;
        $closingHour = $salon->closing_hour ?? 18;
        $closedDays = json_decode($salon->closed_days ?? '[]', true);
        
        if (in_array($start->dayOfWeek, $closedDays)) {
            return back()
                ->withErrors([
                    'scheduled_at' => 'Salon je zatvoren ovog dana.',
                ])
                ->withInput();
        }

        if ($start->hour < $openingHour || $end->hour > $closingHour) {
            return back()
                ->withErrors([
                    'scheduled_at' => "Salon radi od {$openingHour}:00 do {$closingHour}:00.",
                ])
                ->withInput();
        }

        // Prevent overlapping appointments in this salon.
        $overlaps = Appointment::forSalon($salon->id)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->where(function ($q) use ($start, $end): void {
                $q->where('scheduled_at', '<', $end)
                    ->where('ends_at', '>', $start);
            })
            ->exists();

        if ($overlaps) {
            return back()
                ->withErrors([
                    'scheduled_at' => 'Izabrani termin je već zauzet. Molimo izaberite drugi termin.',
                ])
                ->withInput();
        }

        Appointment::create([
            'salon_id' => $salon->id,
            'service_id' => $service->id,
            'client_id' => $user->id,
            'scheduled_at' => $start,
            'ends_at' => $end,
            'status' => 'pending',
            'note' => $request->input('note'),
        ]);

        Notification::create([
            'user_id' => $salon->owner_id,
            'type' => 'info',
            'title' => 'Novi zahtev za termin',
            'content' => "Klijent {$user->name} je zatražio termin za uslugu {$service->name}.",
            'is_visible' => true,
        ]);

        return redirect()
            ->route('appointments.index')
            ->with('status', 'Zahtev za termin je uspešno poslat salonu.');
    }

    /**
     * Get available time slots for a salon on a specific date.
     */
    public function getAvailableSlots(Request $request, Salon $salon)
    {
        try {
            $date = $request->query('date');
            $duration = $request->query('duration', 30);

            if (!$date) {
                return response()->json([]);
            }

            $slots = $salon->getAvailableSlots($date, $duration);

            return response()->json($slots->values()->all());
        } catch (\Exception $e) {
            \Log::error('Available slots error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'error' => 'Greška pri učitavanju termina',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

