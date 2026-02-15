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
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $salon = Auth::user()->salon;

        if (!$salon) {
            return redirect()->route('owner.salon.edit')->with('error', 'Prvo kreirajte salon.');
        }

        $file = $request->file('image');
        $path = $file->store('salons/' . $salon->id, 'public');

        $order = $salon->images()->max('order') ?? 0;

        SalonImage::create([
            'salon_id' => $salon->id,
            'image_url' => '/storage/' . $path,
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
        if ($image->image_url) {
            $path = str_replace('/storage/', '', $image->image_url);
            Storage::disk('public')->delete($path);
        }

        $image->delete();

        return redirect()->route('owner.salon.edit')->with('success', 'Slika je obrisana.');
    }
}
