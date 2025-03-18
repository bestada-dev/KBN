<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Mendapatkan notifikasi untuk pengguna yang sedang login
    public function getNotifications()
    {
        $userId = auth()->id(); // Ambil ID pengguna yang sedang login
        // Ambil notifikasi yang belum dibaca
        $notifications = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->get(['id', 'message', 'url', 'created_at']);


        return response()->json($notifications);
    }

    // Menandai notifikasi sebagai dibaca
    public function markAsRead(Request $request)
    {
        $notificationIds = $request->input('notification_ids');
        $userId = auth()->id(); // Ambil ID pengguna yang sedang login

        // Update status is_read untuk notifikasi berdasarkan ID pengguna
        Notification::whereIn('id', $notificationIds)
            ->where('user_id', $userId)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'success']);
    }
}
