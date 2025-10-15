<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Fetch conversation between two users
    public function fetch($receiver_id)
    {
        $messages = Message::where(function ($q) use ($receiver_id) {
            $q->where('sender_id', Auth::id())
              ->where('receiver_id', $receiver_id);
        })->orWhere(function ($q) use ($receiver_id) {
            $q->where('sender_id', $receiver_id)
              ->where('receiver_id', Auth::id());
        })
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json($messages);
    }

    // Send a message
    public function send(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message'],
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['success' => true]);
    }

    // Mark messages as read
    public function markRead($sender_id)
    {
        Message::where('sender_id', $sender_id)
            ->where('receiver_id', Auth::id())
            ->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    // Get unread message counts for badges
    public function unreadCounts()
    {
        $counts = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as count')
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id');

        return response()->json($counts);
    }
}
