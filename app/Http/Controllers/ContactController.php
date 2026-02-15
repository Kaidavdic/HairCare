<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $settings = json_decode(Storage::get('settings.json') ?? '{}', true);
        return view('contact', ['settings' => $settings]);
    }
}
