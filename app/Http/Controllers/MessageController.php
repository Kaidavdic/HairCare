<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of variables (conversations).
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // Get all messages involving the user
        // This is a simplified approach. Ideally we group by thread.
        // We want unique participants.
        
        $conversations = \App\Models\Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
            })
            ->map(function ($messages) {
                return $messages->first(); // Latest message
            });

        return view('messages.index', ['conversations' => $conversations]);
    }

    /**
     * Show the conversation with a specific user.
     */
    public function show(Request $request, \App\Models\User $user)
    {
        $messages = \App\Models\Message::where(function ($q) use ($request, $user) {
            $q->where('sender_id', $request->user()->id)
              ->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($request, $user) {
            $q->where('sender_id', $user->id)
              ->where('receiver_id', $request->user()->id);
        })
        ->orderBy('created_at')
        ->get();

        // Mark as read
        \App\Models\Message::where('sender_id', $user->id)
            ->where('receiver_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', [
            'otherUser' => $user,
            'messages' => $messages,
        ]);
    }

    /**
     * Store a newly created message in storage.
     */
    public function store(Request $request, \App\Models\User $user)
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'max:5000'],
        ]);

        \App\Models\Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $user->id,
            'content' => $data['content'],
        ]);

        return redirect()->route('messages.show', $user)->with('status', 'Poruka poslata.');
    }
}
