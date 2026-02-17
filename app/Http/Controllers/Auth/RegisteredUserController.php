<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, \App\Services\ImgHippoService $imgHippoService): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:50'],
            'role' => ['required', 'in:salon_owner,client'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'interests' => ['nullable', 'array'],
            'profile_picture' => ['nullable', 'image', 'max:5120'],
        ]);

        $status = 'active'; // All users are active immediately
        $hashedPassword = Hash::make($request->password);
        
        // Generate automatic username
        $baseUsername = strtolower(str_replace(' ', '', $request->name));
        $username = $baseUsername . rand(100, 999);
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . rand(100, 999);
        }

        $interests = $request->interests ?: [];
        $profilePictureUrl = null;

        if ($request->hasFile('profile_picture')) {
            $profilePictureUrl = $imgHippoService->upload($request->file('profile_picture'));
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $status,
            'password' => $hashedPassword,
            'password_history' => [$hashedPassword],
            'interests' => $interests,
            'profile_picture' => $profilePictureUrl,
        ]);

        event(new Registered($user));

        Auth::login($user);
        return redirect(route('home', absolute: false));
    }
}
