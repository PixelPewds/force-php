<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

class NotificationService
{
    /**
     * Create and store a notification.
     *
     * @param int|User $user User ID or User model instance
     * @param string $type Notification type (task_assignment, form_completion, mentor_feedback, resource_added, message)
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array|null $data Additional data to store as JSON
     * @param string|null $actionUrl URL to redirect when user clicks the notification
     *
     * @return Notification
     */
    public function create(
        int|User $user,
        string $type,
        string $title,
        string $message,
        ?array $data = null,
        ?string $actionUrl = null
    ): Notification {
        $userId = $user instanceof User ? $user->id : $user;

        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * Create notifications for multiple users.
     *
     * @param array|Collection $users Array of user IDs or User model instances
     * @param string $type Notification type
     * @param string $title Notification title
     * @param string $message Notification message
     * @param array|null $data Additional data
     * @param string|null $actionUrl URL to redirect
     *
     * @return Collection
     */
    public function createMultiple(
        array|Collection $users,
        string $type,
        string $title,
        string $message,
        ?array $data = null,
        ?string $actionUrl = null
    ): Collection {
        $notifications = collect();

        foreach ($users as $user) {
            $notification = $this->create($user, $type, $title, $message, $data, $actionUrl);
            $notifications->push($notification);
        }

        return $notifications;
    }

    /**
     * Get all unread notifications for a user.
     *
     * @param int|User $user User ID or User model instance
     * @param int $limit Number of notifications to fetch
     *
     * @return Collection
     */
    public function unread(int|User $user, int $limit = 10): Collection
    {
        $userId = $user instanceof User ? $user->id : $user;

        return Notification::where('user_id', $userId)
            ->where('read_at', null)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get all notifications for a user with pagination.
     *
     * @param int|User $user User ID or User model instance
     * @param int $perPage Number of notifications per page
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllPaginated(int|User $user, int $perPage = 15)
    {
        $userId = $user instanceof User ? $user->id : $user;

        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get unread notifications count for a user.
     *
     * @param int|User $user User ID or User model instance
     *
     * @return int
     */
    public function unreadCount(int|User $user): int
    {
        $userId = $user instanceof User ? $user->id : $user;

        return Notification::where('user_id', $userId)
            ->where('read_at', null)
            ->count();
    }

    /**
     * Mark a notification as read.
     *
     * @param int|Notification $notification Notification ID or Notification model instance
     *
     * @return bool
     */
    public function markAsRead(int|Notification $notification): bool
    {
        if ($notification instanceof Notification) {
            $notification->markAsRead();
            return true;
        }

        $notif = Notification::find($notification);
        if ($notif) {
            $notif->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all unread notifications as read for a user.
     *
     * @param int|User $user User ID or User model instance
     *
     * @return int Number of updated notifications
     */
    public function markAllAsRead(int|User $user): int
    {
        $userId = $user instanceof User ? $user->id : $user;

        return Notification::where('user_id', $userId)
            ->where('read_at', null)
            ->update(['read_at' => now()]);
    }

    /**
     * Mark a notification as unread.
     *
     * @param int|Notification $notification Notification ID or Notification model instance
     *
     * @return bool
     */
    public function markAsUnread(int|Notification $notification): bool
    {
        if ($notification instanceof Notification) {
            $notification->markAsUnread();
            return true;
        }

        $notif = Notification::find($notification);
        if ($notif) {
            $notif->markAsUnread();
            return true;
        }

        return false;
    }

    /**
     * Delete a notification.
     *
     * @param int|Notification $notification Notification ID or Notification model instance
     *
     * @return bool
     */
    public function delete(int|Notification $notification): bool
    {
        if ($notification instanceof Notification) {
            return $notification->delete();
        }

        $notif = Notification::find($notification);
        if ($notif) {
            return $notif->delete();
        }

        return false;
    }

    /**
     * Delete all notifications for a user.
     *
     * @param int|User $user User ID or User model instance
     *
     * @return int Number of deleted notifications
     */
    public function deleteAll(int|User $user): int
    {
        $userId = $user instanceof User ? $user->id : $user;

        return Notification::where('user_id', $userId)->delete();
    }

    /**
     * Get notifications by type for a user.
     *
     * @param int|User $user User ID or User model instance
     * @param string $type Notification type
     * @param int $limit Number of notifications to fetch
     *
     * @return Collection
     */
    public function getByType(int|User $user, string $type, int $limit = 10): Collection
    {
        $userId = $user instanceof User ? $user->id : $user;

        return Notification::where('user_id', $userId)
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Send a custom message notification.
     *
     * @param int|User $user User to notify
     * @param string $title Notification title
     * @param string $message Notification message
     * @param string|null $actionUrl URL for the notification
     *
     * @return Notification
     */
    public function notifyMessage(
        int|User $user,
        string $title,
        string $message,
        ?string $actionUrl = null
    ): Notification {
        return $this->create(
            $user,
            'message',
            $title,
            $message,
            null,
            $actionUrl
        );
    }
}
