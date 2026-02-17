<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's public profile.
     */
    public function show(User $user): View
    {
        // Reviews written BY this user (for salons, services, etc.)
        $writtenReviews = $user->reviews()
            ->with(['salon', 'service'])
            ->latest()
            ->take(10)
            ->get();

        // Reviews written ABOUT this user (by salon owners)
        $receivedReviews = $user->receivedReviews()
            ->with(['client', 'appointment.service'])
            ->latest()
            ->take(10)
            ->get();

        return view('profile.show', [
            'user' => $user,
            'writtenReviews' => $writtenReviews,
            'receivedReviews' => $receivedReviews,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, \App\Services\ImgHippoService $imgHippoService): RedirectResponse
    {
        $data = $request->validated();
        
        // Handle interests if they are sent as a string (comma separated)
        if (isset($data['interests']) && is_string($data['interests'])) {
            $data['interests'] = array_map('trim', explode(',', $data['interests']));
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $path = $imgHippoService->upload($request->file('profile_picture'));
            if ($path) {
                $data['profile_picture'] = $path;
            }
        }

        $request->user()->fill($data);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
