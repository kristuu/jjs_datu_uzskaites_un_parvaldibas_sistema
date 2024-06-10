<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function getAllNotifications()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $userPersonCode = $user->person_code;
            $notifications = Notification::where('user_person_code', $userPersonCode)->get();
            $unreadNotificationsCount = $notifications->where('read', false)->count();

            return response()->json(['notifications' => $notifications, 'unread_count' => $unreadNotificationsCount], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function markAsRead(int $notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->read = true;
        $notification->save();

        return response()->json(['message' => 'Notification marked as read'], 200);
    }


    public function destroyNotification(int $id)
    {
        Notification::destroy($id);
    }
}
