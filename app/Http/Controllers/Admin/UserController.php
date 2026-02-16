<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->with('salon')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function warn(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|min:3',
        ]);

        // Send a warning notification from the admin to the target user
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'warning',
            'title' => 'Službeno upozorenje',
            'content' => $request->input('message'),
        ]);

        return back()->with('status', 'Upozorenje poslato korisniku putem obaveštenja.');
    }

    public function ban(User $user)
    {
        // Prevent banning self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Ne možete banovati sami sebe.');
        }

        $user->update(['status' => 'banned']);
        return back()->with('status', 'Korisnik banovan.');
    }

    public function activate(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('status', 'Korisnik aktiviran.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Ne možete obrisati sami sebe.');
        }

        $user->delete();
        return back()->with('status', 'Korisnik obrisan.');
    }
}
