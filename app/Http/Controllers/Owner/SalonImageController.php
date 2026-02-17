<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\SalonImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SalonImageController extends Controller
{
    /**
     * Store a newly uploaded image.
     */
    public function store(Request $request, \App\Services\ImgHippoService $imgHippoService)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $salon = Auth::user()->salon;

        if (!$salon) {
            return redirect()->route('owner.salon.edit')->with('error', 'Prvo kreirajte salon.');
        }

        // Check if salon is approved
        if ($salon->status !== 'approved') {
            return redirect()->route('owner.salon.edit')->with('error', 'Ne možete dodavati slike dok salon nije odobren.');
        }

        $url = $imgHippoService->upload($request->file('image'));
        
        if (!$url) {
             return redirect()->route('owner.salon.edit')->with('error', 'Greška prilikom slanja slike.');
        }

        $order = $salon->images()->max('order') ?? 0;

        SalonImage::create([
            'salon_id' => $salon->id,
            'image_url' => $url,
            'alt_text' => $request->input('alt_text', ''),
            'order' => $order + 1,
        ]);

        return redirect()->route('owner.salon.edit')->with('success', 'Slika je uspešno dodana.');
    }

    /**
     * Delete an image.
     */
    public function destroy(SalonImage $image)
    {
        $salon = Auth::user()->salon;

        if (!$salon || $image->salon_id !== $salon->id) {
            abort(403, 'Unauthorized');
        }

        // Delete file from storage
        // Note: We cannot easily delete from ImgHippo via this API, so we just delete the record.

        $image->delete();

        return redirect()->route('owner.salon.edit')->with('success', 'Slika je obrisana.');
    }
}
