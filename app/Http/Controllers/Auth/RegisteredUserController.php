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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:50'],
            'role' => ['required', 'in:salon_owner,client'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'interests' => ['nullable', 'string', 'max:500'],
        ]);

        $status = $request->role === User::ROLE_SALON_OWNER ? 'pending' : 'active';
        $hashedPassword = Hash::make($request->password);
        
        // Generate automatic username
        $baseUsername = strtolower(str_replace(' ', '', $request->name));
        $username = $baseUsername . rand(100, 999);
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . rand(100, 999);
        }

        $interests = $request->interests;
        if (is_string($interests)) {
            $interests = array_map('trim', explode(',', $interests));
        }
        $interests = $interests ?: [];

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
        ]);

        event(new Registered($user));

        if ($status === 'active') {
            Auth::login($user);
            return redirect(route('home', absolute: false));
        }

        return redirect(route('login'))->with('status', 'Registracija uspešna. Vaš nalog čeka odobrenje administratora.');
    }
}
