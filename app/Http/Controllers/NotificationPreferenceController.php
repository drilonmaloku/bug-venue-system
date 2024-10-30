<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class NotificationPreferenceController extends Controller
{
    public function edit()
    {
        $preferences = auth()->user()->notificationPreference->preferences;
        return view('pages.notifications.preferences', compact('preferences'));

    }
    
    public function update(Request $request)
    {
        $preferences = $request->input('preferences', []);
        auth()->user()->notificationPreference()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['preferences' => $preferences]
        );

        return redirect()->back()->with('status', 'Preferences updated successfully.');
    }
    


}
