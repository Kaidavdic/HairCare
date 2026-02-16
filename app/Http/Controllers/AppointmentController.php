<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * List of appointments for the logged-in client.
     */
    public function index(Request $request): View
    {
        $appointments = $request->user()
            ->appointments()
            ->with(['salon', 'service', 'review'])
            ->orderByDesc('scheduled_at')
            ->paginate(10);

        return view('appointments.index', [
            'appointments' => $appointments,
        ]);
    }

    /**
     * Salon owner's view of all appointments in their salon.
     */
    public function ownerIndex(Request $request): View
    {
        $salon = $request->user()->salon;

        abort_if(! $salon, 404);

        $tab = $request->input('tab', 'all');
        $serviceId = $request->input('service_id');
        $sortRating = $request->input('sort_rating');

        $services = $salon->services()->orderBy('name')->get();

        $query = Appointment::forSalon($salon->id)
            ->with(['client', 'service']);

        // Filter by status (tab)
        if ($tab === 'completed') {
            $query->whereIn('appointments.status', ['completed', 'rejected', 'cancelled']);
        } elseif ($tab === 'active') {
            $query->whereIn('appointments.status', ['pending', 'confirmed']);
        }
        // 'all' tab applies no status filter

        // Filter by service
        if ($serviceId) {
            $query->where('appointments.service_id', $serviceId);
        }

        // Handle Sorting
        if ($sortRating === 'desc') {
            $query->join('users', 'appointments.client_id', '=', 'users.id')
                ->select('appointments.*')
                ->orderByDesc('users.average_rating');
        } elseif ($sortRating === 'asc') {
            $query->join('users', 'appointments.client_id', '=', 'users.id')
                ->select('appointments.*')
                ->orderBy('users.average_rating');
        } else {
            // Default Sorting per tab if no user-defined sort is active
            if ($tab === 'completed') {
                $query->orderByDesc('appointments.scheduled_at');
            } else {
                $query->orderBy('appointments.scheduled_at');
            }
        }

        $appointments = $query->paginate(15)->withQueryString();

        return view('owner.appointments.index', [
            'salon' => $salon,
            'appointments' => $appointments,
            'services' => $services,
            'currentTab' => $tab,
            'filters' => [
                'service_id' => $serviceId,
                'sort_rating' => $sortRating,
            ],
        ]);
    }

    /**
     * Client cancels their own appointment.
     */
    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        if ($appointment->client_id !== $request->user()->id) {
            abort(403);
        }

        if (! in_array($appointment->status, ['pending', 'confirmed'], true)) {
            return back()->withErrors([
                'status' => 'Ovaj termin nije moguće otkazati.',
            ]);
        }

        $appointment->update([
            'status' => 'cancelled',
        ]);

        return back()->with('status', 'Termin je otkazan.');
    }

    /**
     * Salon owner updates appointment status.
     */
    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $salon = $request->user()->salon;

        if (! $salon || $appointment->salon_id !== $salon->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,rejected,completed'],
        ]);

        $appointment->update([
            'status' => $validated['status'],
        ]);

        return back()->with('status', 'Status termina je ažuriran.');
    }
}

