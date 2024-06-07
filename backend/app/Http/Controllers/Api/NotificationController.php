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

            if ($notifications->isEmpty()) {
                return response()->json(['message' => 'No notifications found'], 204);
            }

            return response()->json(['notifications' => $notifications], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }


    public function destroyNotification(int $id)
    {
        Notification::destroy($id);
    }
}
