<?php

namespace App\Modules\Users\Services;

use App\Models\User;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use App\Modules\Users\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UsersService
{
    public $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

     /**
     * Retrieve all users with roles of 'admin' or 'super-admin'.
     *
     * @return \Illuminate\Database\Eloquent\Collection The collection of users with admin or super-admin roles.
     */
    public function getAll($request,$withoutPagination = false){
        $query = User::query();
        if ($request && $request->has("search")) {
            $searchTerm = '%' . $request->input("search") . '%';

            $query->where(function ($subquery) use ($searchTerm) {
                $subquery->where('first_name', 'LIKE', $searchTerm)
                    ->orWhere('email', 'LIKE', $searchTerm)
                    ->orWhere('last_name', 'LIKE', $searchTerm)
                    ->orWhere('username', 'LIKE', $searchTerm);
            });
        }
    
        if($withoutPagination) {
            return $query->get();
        }
        return $query->paginate(50);
    }

     /**
     * Retrieve archived users based on the provided request parameters.
     *
     * @param Request $request The HTTP request object containing query parameters.
     * @return \Illuminate\Pagination\LengthAwarePaginator A paginated list of archived users.
     */
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
            $query->whereIn("name", ["admin","super-admin"]);
        });

        $users = $usersQuery
            ->onlyTrashed()
            ->paginate(data_get($request, "per_page") ?? 10);

        $users->appends($request->all());


        return $users;
    }

     /**
     * Retrieve paginated list of users based on the provided request parameters.
     *
     * @param Request $request The HTTP request object containing query parameters.
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginated(Request $request){
        $perPage = $request->has('per_page') ? $request->input('per_page') : 50;
        $query = User::query();
        $query->whereHas("roles", function ($query) {
            $query->whereIn("name", ["admin","super-admin"]);
        });
        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Register a new user with the provided information.
     *
     * @param array $request The request data containing information for the new user.
     * @return void
     */
    public function registerUser($request)
    {
        $user = (new User)->create([
            "name" => data_get($request, "name"),
            "email" => data_get($request, "email"),
            "phone" => data_get($request, "phone"),
            "password" => Hash::make(data_get($request, "password")) 
                ?? Str::random(12),
        ]);

        if (data_get($request, "role") === "staff") {
            $user->assignRole("staff");
        }

        $user->notify(new UserNotification($user));
    }

    /**
     * Set the password for a user.
     *
     * @param Request $request The HTTP request object containing user ID and new password.
     * @return void
     */
    public function setPassword(Request $request)
    {
        $user = (new User)->find(data_get($request, "user"));
        
        $user->password = Hash::make(data_get($request, "password"));
        $user->save();

        Auth::login($user);
    }

    /**
     * Get User by ID
     * @param bool $id
     **/
    public function getByID($id,$withTrashed = false){

        return $withTrashed ?  User::withTrashed()->find($id) : User::find($id);
    }

    /**
     * Retrieve multiple users based on the provided IDs.
     *
     * @param array $ids An array containing the IDs of the users to retrieve.
     * @param bool $withTrashed Optional. Whether to include soft-deleted users. Default is false.
     * @return \Illuminate\Database\Eloquent\Collection A collection of users.
     */
    public function getMultiple($ids,$withTrashed = false){

        return $withTrashed ?  User::withTrashed()->whereIn('id',$ids)->get() : User::whereIn('id',$ids)->get();
    }

    /**
     * Store a new user based on the provided request data.
     *
     * 
     * @return User|null The newly created user instance, or null if creation fails.
     */
    public function store($request) {
        $user = User::create([
            "username" => $request->input("username"),
            "first_name" => $request->input("first_name"),
            "last_name" => $request->input("last_name"),
            "email" => $request->input("email"),
            "phone" => $request->input("phone"),
            "password" => Hash::make($request->input("password")),
            ]
        );

        $user->assignRole($request->input("role"));
        return $user;
    }


    /**
     * Delete a user and log the action.
     *
     * @param User $user The user instance to be deleted.
     * @return bool True if the user was deleted successfully, otherwise false.
     */
    public function destroy($user)
    {
        $userDeleted = $user->delete();
        if($userDeleted) {
            $log = $this->logService->store([
                "message" => auth()->user()->name . " deleted user with id: ".$user->id. 'and name: '.$user->name,
                "context" => Log::LOG_CONTEXT_USERS,
                "ttl" => Log::LOG_TTL_FOREVER,
                "keep_alive" => Log::LOG_TTL_KEEP_ALIVE,
            ]);
        }
        return $user->delete();
    }
    /**
     * Reset the password for a user.
     *
     * @param array $request The request data containing user ID and new password.
     * @return bool True if the password was reset successfully, otherwise false.
     */
    public function resetPassword($request)
    {
        $user = (new User)->find(data_get($request, "user"));
        $updatedUser = $user->update([
            "password" => Hash::make(data_get($request, "password")),
        ]);

        return $updatedUser;
    }

    /**
     * Archive a user by soft-deleting it.
     *
     * @param User $user The user instance to be archived.
     * @return bool True if the user was archived successfully, otherwise false.
     */
    public function archive($user)
    {
        return $user->delete();
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param \App\Models\User $user The soft-deleted user instance to be restored.
     * @return bool True if the user was restored successfully, otherwise false.
     */
    public function restore($user)
    {
        return $user->restore();
    }

    /**
     * Restore multiple soft-deleted users in bulk.
     *
     * @param \Illuminate\Database\Eloquent\Collection $users The collection of soft-deleted user instances to be restored.
     * @return int The number of users restored.
     */
    public function restoreBulk($users)
    {
        $userIds = $users->pluck('id')->toArray();

        $updatedCount = DB::table('users')->whereIn('id', $userIds)->update(['deleted_at' => null]);

        return $updatedCount;
    }

    /**
     * Permanently delete a user from the system.
     *
     * @param User $user The user instance to be permanently deleted.
     * @return bool True if the user was permanently deleted successfully, otherwise false.
     */
    public function forceDelete($user){
        Storage::deleteDirectory("public/staff-files/user/{$user->id}");
        return $user->forceDelete();
    }



    /**
     * Updates existing client
     **/
    public function update($request, User $user) {
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        $clientSaved = $user->save();

        if($clientSaved){
            $this->logService->log([
                'message' => 'User was updated successfully',
                'context' => Log::LOG_CONTEXT_USERS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $user;
    }


}
