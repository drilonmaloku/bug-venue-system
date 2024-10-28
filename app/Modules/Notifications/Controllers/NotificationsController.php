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
        return view('pages.notifications.index',  [
            'notifications' => $notifications
        ]);
    }

    public function archive(Request $request){
        $notifications = $this->notificationsService->getAll();
        return view('pages.notifications.index',  [
            'notifications' => $notifications
        ]);
      
    }

    public function markNotificationAsRead(DatabaseNotification $notification)
    {

        $this->notificationsService->markNotificationAsRead($notification);
         
                alert()->success(
                    'Notification u be read me sukses'
                )->autoclose(2000);
        return redirect()->to('notifications');
    }

    /**
     *  Mark Notification as Unread
     **/
    public function markNotificationAsUnread(DatabaseNotification $notification)
    {
        $this->notificationsService->markNotificationAsUnread($notification);
                   alert()->success(
                    'Notification u be unread me sukses'
                )->autoclose(2000);
        return redirect()->to('notifications');
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
