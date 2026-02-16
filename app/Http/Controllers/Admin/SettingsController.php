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
        $settings = json_decode(Storage::get('settings.json') ?? '{}', true);

        $data = $request->validate([
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_content' => ['required', 'string'],
            'hero_bg_image' => ['nullable', 'image', 'max:5120'], // Max 5MB
            'password_history_count' => ['required', 'integer', 'min:0', 'max:10'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // Handle image upload
        if ($request->hasFile('hero_bg_image')) {
            // Delete old image if it exists
            if (isset($settings['hero_bg_image'])) {
                Storage::disk('public')->delete($settings['hero_bg_image']);
            }
            
            $path = $request->file('hero_bg_image')->store('hero', 'public');
            $data['hero_bg_image'] = $path;
        } else {
            // Keep existing image if no new one uploaded
            $data['hero_bg_image'] = $settings['hero_bg_image'] ?? null;
        }

        Storage::put('settings.json', json_encode($data));

        return back()->with('status', 'Podešavanja sačuvana.');
    }
}
