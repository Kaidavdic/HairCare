<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a review for a completed appointment.
     */
    public function store(Request $request, Appointment $appointment): RedirectResponse
    {
        $user = $request->user();
        $isOwner = $user->isSalonOwner();

        // Check permission
        if ($isOwner) {
            if ($appointment->salon->owner_id !== $user->id) {
                abort(403);
            }
        } elseif ($appointment->client_id !== $user->id) {
             abort(403);
        }

        if ($appointment->status !== 'completed') {
            return back()->withErrors([
                'rating' => 'Recenziju možete ostaviti tek nakon završenog termina.',
            ]);
        }

        $type = $request->input('type', 'service'); // 'service' is default (Client -> Salon)

        // Check if review of this type already exists
        $existingReview = $appointment->reviews()->where('type', $type)->first();
        if ($existingReview) {
             return back()->withErrors([
                'rating' => 'Recenzija je već ostavljena.',
            ]);
        }

        $data = $request->validate([
            'service_rating' => ['required_if:type,service', 'nullable', 'integer', 'min:1', 'max:5'],
            'salon_rating' => ['required_if:type,service', 'nullable', 'integer', 'min:1', 'max:5'],
            'rating' => ['required_if:type,user', 'nullable', 'integer', 'min:1', 'max:5'], // Keep for user rating
            'comment' => ['nullable', 'string', 'max:2000'],
            'type' => ['in:service,user'],
        ]);

        if ($type === 'user') {
            // Owner rating Client
            $review = $appointment->reviews()->create([
                'salon_id' => $appointment->salon_id,
                'client_id' => $appointment->client_id,
                'reviewed_user_id' => $appointment->client_id,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
                'type' => 'user',
            ]);

            // Update User aggregate statistics
            $client = $appointment->client;
            if ($client) {
                $currentCount = $client->reviews_count;
                $currentAverage = $client->average_rating;

                $newCount = $currentCount + 1;
                $newAverage = (($currentAverage * $currentCount) + $review->rating) / $newCount;

                $client->reviews_count = $newCount;
                $client->average_rating = $newAverage;
                $client->save();
            }
        } else {
             // Client rating Salon AND Service
            $review = $appointment->reviews()->create([
                'salon_id' => $appointment->salon_id,
                'service_id' => $appointment->service_id,
                'client_id' => $user->id,
                'service_rating' => $data['service_rating'],
                'salon_rating' => $data['salon_rating'],
                'rating' => $data['salon_rating'], // Legacy fallback
                'comment' => $data['comment'] ?? null,
                'type' => 'service',
            ]);

            // Update salon statistics (Based on salon_rating)
            $salon = $appointment->salon;
            if ($salon) {
                $currentCount = $salon->reviews_count;
                $currentAverage = $salon->average_rating;

                $newCount = $currentCount + 1;
                $newAverage = (($currentAverage * $currentCount) + $review->salon_rating) / $newCount;

                $salon->reviews_count = $newCount;
                $salon->average_rating = $newAverage;
                $salon->save();
            }

            // Update service statistics (Based on service_rating)
            $service = $appointment->service;
            if ($service) {
                $currentCount = (int) $service->reviews_count;
                $currentAverage = (float) $service->average_rating;

                $newCount = $currentCount + 1;
                $newAverage = (($currentAverage * $currentCount) + $review->service_rating) / $newCount;

                $service->reviews_count = $newCount;
                $service->average_rating = $newAverage;
                $service->save();
            }
        }

        return back()->with('status', 'Hvala na recenziji!');
    }
}

