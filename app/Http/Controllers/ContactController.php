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
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('contact', ['settings' => $settings]);
    }
}
