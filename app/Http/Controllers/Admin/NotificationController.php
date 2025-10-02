<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Show notifications
    public function managenotifications()
    {
        $notifications = Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.manage-notifications', compact('notifications'));
    }


   // Mark a notification as read
    public function markAsRead($id)
    {
        $notif = Notification::findOrFail($id);
        $notif->read = true;   // ✅ assumes you have a `read` (boolean) column
        $notif->save();

        return back()->with('success', 'Notification marked as read.');
    }

    // Delete notification
    public function destroy($id)
    {
        $notif = Notification::findOrFail($id);
        $notif->delete();

        return back()->with('success', 'Notification deleted.');
    }

    public function fetch()
    {
        // Example: fetch only unread notifications for the logged-in patient
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        return response()->json($notifications);
    }
}
