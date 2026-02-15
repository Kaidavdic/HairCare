<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = \App\Models\Notification::whereNull('user_id')
            ->latest()
            ->paginate(10);
        return view('admin.notifications.index', ['notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_visible' => 'sometimes',
        ]);

        $data['is_visible'] = $request->has('is_visible');
        $data['user_id'] = null; // Ensure this is a global notification
        $data['type'] = 'info';  // Global notifications are usually info type

        \App\Models\Notification::create($data);

        return redirect()->route('admin.notifications.index')->with('status', 'Notifikacija kreirana.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Notification $notification)
    {
        return view('admin.notifications.edit', ['notification' => $notification]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Notification $notification)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_visible' => 'sometimes',
        ]);

        $data['is_visible'] = $request->has('is_visible');

        $notification->update($data);

        return redirect()->route('admin.notifications.index')->with('status', 'Notifikacija ažurirana.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('status', 'Notifikacija obrisana.');
    }
}
