<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Models\Notification create(int|\App\Models\User $user, string $type, string $title, string $message, ?array $data = null, ?string $actionUrl = null)
 * @method static \Illuminate\Database\Eloquent\Collection createMultiple(array|\Illuminate\Database\Eloquent\Collection $users, string $type, string $title, string $message, ?array $data = null, ?string $actionUrl = null)
 * @method static \Illuminate\Database\Eloquent\Collection unread(int|\App\Models\User $user, int $limit = 10)
 * @method static \Illuminate\Pagination\LengthAwarePaginator getAllPaginated(int|\App\Models\User $user, int $perPage = 15)
 * @method static int unreadCount(int|\App\Models\User $user)
 * @method static bool markAsRead(int|\App\Models\Notification $notification)
 * @method static int markAllAsRead(int|\App\Models\User $user)
 * @method static bool markAsUnread(int|\App\Models\Notification $notification)
 * @method static bool delete(int|\App\Models\Notification $notification)
 * @method static int deleteAll(int|\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Collection getByType(int|\App\Models\User $user, string $type, int $limit = 10)
 * @method static \App\Models\Notification notifyTaskAssignment(int|\App\Models\User $user, string $taskTitle, string $description, ?string $actionUrl = null)
 * @method static \App\Models\Notification notifyFormCompletion(int|\App\Models\User $user, string $formTitle, string $message = '', ?string $actionUrl = null)
 * @method static \App\Models\Notification notifyMentorFeedback(int|\App\Models\User $user, string $mentorName, string $feedback, ?string $actionUrl = null)
 * @method static \App\Models\Notification notifyResourceAdded(int|\App\Models\User $user, string $resourceName, string $resourceType = 'Resource', ?string $actionUrl = null)
 * @method static \App\Models\Notification notifyMessage(int|\App\Models\User $user, string $title, string $message, ?string $actionUrl = null)
 *
 * @see \App\Services\NotificationService
 */
class Notification extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'notification-service';
    }
}
