<?php

namespace App\Http\Controllers;

use App\Facades\Notification;
use App\Models\Notification as NotificationModel;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * Display all notifications for the authenticated user
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead(NotificationModel $notification)
    {
        // Check if the notification belongs to the authenticated user
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Notification::markAsRead($notification);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Notification marked as read']);
        }

        return redirect()->back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read for the authenticated user
     */
    public function markAllAsRead()
    {
        Notification::markAllAsRead(auth()->user());

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
        }

        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    /**
     * Mark a notification as unread
     */
    public function markAsUnread(NotificationModel $notification)
    {
        // Check if the notification belongs to the authenticated user
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Notification::markAsUnread($notification);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Notification marked as unread']);
        }

        return redirect()->back()->with('success', 'Notification marked as unread');
    }

    /**
     * Delete a notification
     */
    public function delete(NotificationModel $notification)
    {
        // Check if the notification belongs to the authenticated user
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Notification::delete($notification);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Notification deleted']);
        }

        return redirect()->back()->with('success', 'Notification deleted');
    }

    /**
     * Delete all notifications for the authenticated user
     */
    public function deleteAll()
    {
        Notification::deleteAll(auth()->user());

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'All notifications deleted']);
        }

        return redirect()->back()->with('success', 'All notifications deleted');
    }

    /**
     * Get notifications as JSON (for AJAX requests)
     */
    public function getNotifications(Request $request)
    {
        $limit = $request->get('limit', 10);
        $notifications = Notification::unread(auth()->user(), limit: (int) $limit);

        return response()->json([
            'count' => Notification::unreadCount(auth()->user()),
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'action_url' => $notification->action_url,
                ];
            }),
        ]);
    }
}
