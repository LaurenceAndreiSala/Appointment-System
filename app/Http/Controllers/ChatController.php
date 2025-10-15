<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index($receiver_id)
    {
        $receiver = User::findOrFail($receiver_id);
        $messages = Message::where(function($q) use ($receiver_id) {
                $q->where('sender_id', Auth::id())
                  ->where('receiver_id', $receiver_id);
            })
            ->orWhere(function($q) use ($receiver_id) {
                $q->where('sender_id', $receiver_id)
                  ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('doctor.chat', compact('receiver', 'messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate(['receiver_id' => 'required', 'message' => 'required']);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['success' => true]);
    }
}
