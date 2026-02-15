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

        $appointments = Appointment::forSalon($salon->id)
            ->with(['client', 'service'])
            ->orderBy('scheduled_at')
            ->paginate(15);

        return view('owner.appointments.index', [
            'salon' => $salon,
            'appointments' => $appointments,
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

