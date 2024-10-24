<?php

namespace App\Modules\Notifications\Controllers;

use App\Modules\Notifications\Resources\NotificationResource;
use App\Modules\Notifications\Services\NotificationsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsController extends Controller
{
    public $notificationsService;

    public function __construct(NotificationsService $notificationsService)
    {
        $this->notificationsService = $notificationsService;
    }

    public function index(){
        $notifications = $this->notificationsService->getAll();
        return response()->json([
            'data' =>NotificationResource::collection($notifications)
        ]);
    }

    public function archive(Request $request){
        $notifications = $this->notificationsService->getAllArchiveNotifications($request);
        return response()->json([
            'data' =>NotificationResource::collection($notifications->items())
        ]);
    }

    public function markNotificationAsRead(DatabaseNotification $notification)
    {
        $this->notificationsService->markNotificationAsRead($notification);
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ],200);
    }

    /**
     *  Mark Notification as Unread
     **/
    public function markNotificationAsUnread(DatabaseNotification $notification)
    {
        $this->notificationsService->markNotificationAsUnread($notification);
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as unread'
        ],200);
    }

    /**
     * Mark all user notifications as read
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllNotificationsAsRead()
    {
        $this->notificationsService->markNotificationsAsReadOrUnread();

        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read'
        ],200);
    }

}
