<?php

namespace App\Modules\Users\Services;

use App\Models\User;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UsersService
{
    public $logService;

    public function __construct()
    {
        $this->logService = app()->make(LogService::class);
    }

    public function getByIds($ids){
        return User::whereIn('id', $ids)->get();
    }

    public function getAll($request,$withoutPagination = false){
        $query = User::query();


        if ($request && $request->has("search") && $request->input("search") != '') {
            $searchTerm = '%' . $request->input("search") . '%';

            $query->where(function ($subquery) use ($searchTerm) {
                $subquery->where('first_name', 'LIKE', $searchTerm)
                    ->orWhere('email', 'LIKE', $searchTerm)
                    ->orWhere('last_name', 'LIKE', $searchTerm)
                    ->orWhere('username', 'LIKE', $searchTerm);
            });
        }
        if ($request && $request->has('role')  && $request->input("role") != '') {
            $role = $request->input('role');

            $query->whereHas('roles', function ($subquery) use ($role) {
                $subquery->where('name', $role);
            });
        }

        $locationId = auth()->user()->getCurrentLocationId();

        if ($locationId) {
            $query->whereHas('locations', function ($subquery) use ($locationId) {
                $subquery->where('location_id', $locationId);
            });
        }

        if($withoutPagination) {
            return $query->get();
        }
        $query->orderBy('created_at', 'desc');


        return $query->paginate(50);
    }

    public function getArchived($request){

        $usersQuery = (new User)->query()->onlyTrashed();
        $search = data_get($request, "search");

        $usersQuery
            ->where(function ($query) use ($search) {
                $query
                ->where("username", "like", "%" . $search . "%")
                    ->where("first_name", "like", "%" . $search . "%")
                    ->where("last_name", "like", "%" . $search . "%")
                    ->orWhere("email", "like", "%" . $search . "%");
            });

        $usersQuery->orderBy(
            data_get($request, "order_by") ?? "created_at",
            data_get($request, "sort_direction") ?? "desc"
        );
        $usersQuery->whereHas("roles", function ($query) {
            $query->whereIn("name", ["admin","-adminsuper"]);
        });

        $users = $usersQuery
            ->onlyTrashed()
            ->paginate(data_get($request, "per_page") ?? 10);

        $users->appends($request->all());


        return $users;
    }

    public function getPaginated(Request $request){
        $perPage = $request->has('per_page') ? $request->input('per_page') : 50;
        $query = User::query();
        $query->whereHas("roles", function ($query) {
            $query->whereIn("name", ["admin","super-admin"]);
        });
        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    public function setPassword(Request $request)
    {
        $user = (new User)->find(data_get($request, "user"));
        
        $user->password = Hash::make(data_get($request, "password"));
        $user->save();

        Auth::login($user);
    }

    public function getByID($id,$withTrashed = false){

        return $withTrashed ?  User::withTrashed()->find($id) : User::find($id);
    }

    public function getMultiple($ids,$withTrashed = false){

        return $withTrashed ?  User::withTrashed()->whereIn('id',$ids)->get() : User::whereIn('id',$ids)->get();
    }

    public function store($request) {
        $username = auth()->user()->getCurrentLocationId() ? auth()->user()->getCurrentLocationSlug().'_'.$request->input("username") : $request->input("username");
                                    
        $user = User::create([
            "username" => $username,
            "first_name" => $request->input("first_name"),
            "last_name" => $request->input("last_name"),
            "email" => $request->input("email"),
            "phone" => $request->input("phone"),
            "language" => $request->input("language"),
            "password" => Hash::make($request->input("password")),
            ]
        );

        $user->assignRole($request->input("role"));
        

         $user->notificationPreferences()->create([
            'preferences' => $user->defaultNotificationPreferences(),
        ]);
        return $user;
    }

    public function destroy(User $user)
    {
        $userDeleted = $user->delete();

        if ($userDeleted) {
            $this->logService->store([
                "message" => auth()->user()->name . " deleted user with id: " . $user->id . ' and name: ' . $user->name,
                "context" => Log::LOG_CONTEXT_USERS,
                "ttl" => Log::LOG_TTL_FOREVER,
                "keep_alive" => Log::LOG_TTL_KEEP_ALIVE,
            ]);
        }

        return $userDeleted;
    }

    public function resetPassword($request)
    {
        $user = (new User)->find(data_get($request, "user"));
        $updatedUser = $user->update([
            "password" => Hash::make(data_get($request, "password")),
        ]);

        return $updatedUser;
    }

    public function archive($user)
    {
        return $user->delete();
    }

    public function restore($user)
    {
        return $user->restore();
    }

    public function restoreBulk($users)
    {
        $userIds = $users->pluck('id')->toArray();

        $updatedCount = DB::table('users')->whereIn('id', $userIds)->update(['deleted_at' => null]);

        return $updatedCount;
    }

    public function forceDelete($user){
        Storage::deleteDirectory("public/staff-files/user/{$user->id}");
        return $user->forceDelete();
    }

    public function update($request, User $user) {

        $user->username = $request->input('username');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->language = $request->input('language');

        $user->userSettings()->updateOrCreate(
            [
                'location_id' => $user->getCurrentLocationId(),
                'user_id' => $user->id
            ],
            [
                'location_id' => $user->getCurrentLocationId(),
                'event_title_template' => $request->input('event_title_template'),
            ]
        );

        $userSaved = $user->save();

        if($userSaved){
            $this->logService->log([
                'message' => 'Përdoruesi u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_USERS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $user;
    }

    public function updatePassword(Request $request, User $user)
    {
        $oldPassword = $request->input('password_old');
        $newPassword = $request->input('password_new');
        
        if (!Hash::check($oldPassword, $user->password)) {
            
            return false;
        }

        $user->password = Hash::make($newPassword);

        return $user->save();
    }

    public function getStaffUsers()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'staff');
        })->get();
    }

    public function getUsersForNotifications($notificationKey){
        $user = auth()->user();
        $currentLocationId = $user->getCurrentLocationId();

        $users = User::whereHas('locations', function ($query) use ($currentLocationId) {
            $query->where('locations.id', $currentLocationId);
        })
        ->whereHas('notificationPreferences', function ($query) use ($notificationKey) {
            $query->where('preferences->' . $notificationKey, true);
        })
        ->where('id', '!=', $user->id)
        ->get();

        return $users;
    }

    public function updateNotificationPreferences($preferences)
    {
        auth()->user()->notificationPreferences()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['preferences' => $preferences]
        );
    }

   
}
