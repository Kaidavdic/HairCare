<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = json_decode(Storage::get('settings.json') ?? '{}', true);
        return view('admin.settings.edit', ['settings' => $settings]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'password_history_count' => ['required', 'integer', 'min:0', 'max:10'],
        ]);

        Storage::put('settings.json', json_encode($data));

        return back()->with('status', 'Podešavanja sačuvana.');
    }
}
