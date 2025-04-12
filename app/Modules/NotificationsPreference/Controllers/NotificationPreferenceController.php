<?php

namespace App\Modules\NotificationsPreference\Controllers;
use App\Modules\Users\Services\UsersService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class NotificationPreferenceController extends Controller
{

    private $usersService;
 

    public function __construct(
        UsersService $usersService
    )
    {
        $this->usersService = $usersService;
    }

    public function edit()
    {
        $preferences = auth()->user()->notificationPreferences ? auth()->user()->notificationPreferences->preferences : [];

        return view('pages.notifications.preferences',[
            'preferences' => $preferences
        ]);

    }
    
    public function update(Request $request)
    {
        $preferences = $request->input('preferences', []);
        $preferences = array_map(function ($value) {
            return $value === 'on';
        }, $preferences);
        $this->usersService->updateNotificationPreferences($preferences);


        return redirect()->back()->with('status', 'Preferences updated successfully.');
    }
  

}
