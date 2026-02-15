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
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'type' => ['in:service,user'],
        ]);

        if ($type === 'user') {
            // Owner rating Client
            $review = $appointment->reviews()->create([
                'salon_id' => $appointment->salon_id,
                'client_id' => $appointment->client_id, // Author is owner, but we link to appointment
                'reviewed_user_id' => $appointment->client_id, // User being reviewed
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
             // Client rating Salon (and optionally Service)
            $review = $appointment->reviews()->create([
                'salon_id' => $appointment->salon_id,
                'client_id' => $user->id,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
                'type' => 'service',
            ]);

            // Update salon statistics
            $salon = $appointment->salon;
            if ($salon) {
                $currentCount = $salon->reviews_count;
                $currentAverage = $salon->average_rating;

                $newCount = $currentCount + 1;
                $newAverage = $newCount > 0
                    ? (($currentAverage * $currentCount) + $review->rating) / $newCount
                    : $review->rating;

                $salon->reviews_count = $newCount;
                $salon->average_rating = $newAverage;
                $salon->save();
            }
        }

        return back()->with('status', 'Hvala na recenziji!');
    }
}

