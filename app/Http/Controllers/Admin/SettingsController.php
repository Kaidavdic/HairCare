<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.edit', ['settings' => $settings]);
    }

    public function update(Request $request, \App\Services\ImgHippoService $imgHippoService)
    {
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
             $url = $imgHippoService->upload($request->file('hero_bg_image'));
             if ($url) {
                 \App\Models\Setting::setValue('hero_bg_image', $url);
             }
        }

        // Save other settings
        foreach ($data as $key => $value) {
            if ($key !== 'hero_bg_image') {
                \App\Models\Setting::setValue($key, $value);
            }
        }

        return back()->with('status', 'Podešavanja sačuvana.');
    }
}
