<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        $history = $user->password_history ?? [];
        
        // Load history limit from settings
        $settings = json_decode(\Illuminate\Support\Facades\Storage::get('settings.json') ?? '{}', true);
        $historyLimit = $settings['password_history_count'] ?? 3;

        // Check against history
        foreach ($history as $oldHash) {
            if (Hash::check($validated['password'], $oldHash)) {
                return back()->withErrors(['password' => __('validation.password_history', ['count' => $historyLimit])], 'updatePassword')
                    ->withInput();
            }
        }

        // Add to history
        $newHash = Hash::make($validated['password']);
        $history[] = $newHash;
        if (count($history) > $historyLimit) {
            array_shift($history);
        }

        $user->update([
            'password' => $newHash,
            'password_history' => $history,
        ]);

        return back()->with('status', 'password-updated');
    }
}
