<?php namespace App\Modules\Notifications\Services;

use Illuminate\Notifications\DatabaseNotification;



class NotificationsService
{

    public function getAllArchiveNotifications($request){
        $notifications = null;
        if($request->filled('show_all') && $request->query('show_all') == true ) {
            $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc') ->paginate(50);
        }else {
            $notifications = auth()->user()->readNotifications()->orderBy('created_at', 'desc')->paginate(50);
        }
        return $notifications;
    }

    /**
     *  Get all unread notifications
     **/
    public function getAll(){
        return auth()->user()->unreadNotifications;
    }

    /**
     *  Mark Notification as Read
     **/
    public function markNotificationAsRead(DatabaseNotification $notification)
    {
        return $notification->markAsRead();
    }

    /**
     *  Mark Notification as Unread
     **/
    public function markNotificationAsUnread(DatabaseNotification $notification)
    {
        return $notification->markAsUnread();
    }

    /**
     * Mark all user notifications as read or unread
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markNotificationsAsReadOrUnread($markAsRead = true)
    {
        $unreadNotifications = auth()->user()->unreadNotifications;

        foreach ($unreadNotifications as $notification) {
            if($markAsRead) {
                $notification->markAsRead();
            }else {
                $notification->markAsUnread();
            }
        }
    }

}
