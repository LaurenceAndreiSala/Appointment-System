<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(Request $request)
{
    $message = Message::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $request->receiver_id,
        'message'    => $request->message,
    ]);

    // 🔔 Broadcast event so receiver gets it live
    broadcast(new \App\Events\MessageSent($message))->toOthers();

    return response()->json($message);
}

public function fetch($userId)
{
    $messages = Message::where(function($q) use ($userId) {
        $q->where('sender_id', auth()->id())->where('receiver_id', $userId);
    })->orWhere(function($q) use ($userId) {
        $q->where('sender_id', $userId)->where('receiver_id', auth()->id());
    })->orderBy('created_at')->get();

    return response()->json($messages);
}
 
}
