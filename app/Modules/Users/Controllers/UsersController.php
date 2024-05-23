<?php

declare(strict_types=1);

namespace App\Modules\Users\Controllers;
use Inertia\Inertia;

use App\Models\User;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Modules\Users\Exports\UsersExport;
use App\Modules\Users\Services\UsersService;
use App\Modules\Logs\Resources\LogListResource;
use App\Modules\Users\Resources\UserListResource;
use App\Modules\Users\Requests\UpdateUserRequest;
use App\Modules\Users\Requests\CreateUserRequest;
use App\Modules\Users\Requests\RegisterUserRequest;
use App\Modules\Groups\Resources\GroupsListResource;

class UsersController extends Controller
{
    public $usersService;

    public $logService;

    public function __construct(
        UsersService $usersService,
        LogService $logService
    )
    {
        $this->usersService = $usersService;

        $this->logService = $logService;
    }

    /**
     * Display a listing of all users.
     *
     * Note: Currently everything is managed from the frontend.
     *
     * @return Response The rendered view displaying the users.
     */
    public function index(Request $request){
        // $users = $this->usersService->getAll();

        $users = $this->usersService->getAll($request);

        return view('pages/users/index',[
            'is_on_search'=>count($request->all()),
            'users'=>$users
        ]);
    }



    public function view($id)
    {
        $users = $this->usersService->getByID($id);
        if(is_null($users)) {
            return abort(404);
        }
        return view('pages/users/show',[
            'users'=>$users
        ]);
    }

    public function edit($id)
    {
        $user = $this->usersService->getByID($id);
        if(is_null($user)) {
            return abort(404);
        }
        return view('pages/users/edit',[
            'user'=>$user
        ]);
    }


    public function create()
    {
        return view('pages/users/create');
    }



    /**
     * Store a new user.
     *
     * @param CreateUserRequest $request The request object containing user creation data.
     * @return JsonResponse A JSON response indicating the success of the operation.
     */
    public function store(CreateUserRequest $request)
    {

        $user = $this->usersService->store($request);

        if($user) {
             $this->logService->log([
            'message' => 'New client created with id: '.$user->id,
            'context' => Log::LOG_CONTEXT_CLIENTS,
            'ttl'=> Log::LOG_TTL_SIX_MONTHS
       ]);
        }
        return redirect()->to('users');
    }

    // public function store(Request $request) {
    //     $venue = $this->venuesService->store($request);

    //     return redirect()->to('venues');
    //     try {


    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Internal Server Error'
    //         ], 500);
    //     }
    // }

    /**
     * Display the specified user.
     *
     * @param int $id The ID of the user to display.
     * @return Response The rendered view displaying the user and related data.
     */
    public function show($id)
    {
        $user = $this->usersService->getByID($id);

        if(is_null($user)) {
            return abort(404);
        }

        $groups = $this->groupsService->getAllGroupsForUser($user->id);
        $activeGroups = [];
        $archivedGroups = [];

        foreach ($groups as $group) {
            if($group->trashed()) {
                array_push($archivedGroups,$group);
            }else {
                array_push($activeGroups,$group);
            }
        }


        return Inertia::render('Users/Single', [
            'user' => UserListResource::make($user),
            'active_groups' => GroupsListResource::collection($activeGroups),
            'archived_groups' => GroupsListResource::collection($archivedGroups),
            'logs' => LogListResource::collection($user->logs()->latest()->take(25)->get())
        ]);
    }

    /**
     * Display the authenticated user's profile.
     *
     * @return Response The rendered view displaying the user's profile and related data.
     */
    public function profile()
    {
        $user = auth()->user();
        $groups = $this->groupsService->getAllGroupsForUser($user->id);
        $activeGroups = [];
        $archivedGroups = [];

        foreach ($groups as $group) {
            if($group->trashed()) {
                array_push($archivedGroups,$group);
            }else {
                array_push($activeGroups,$group);
            }
        }

        return Inertia::render('Users/Single', [
            'user' => UserListResource::make($user),
            'active_groups' => GroupsListResource::collection($activeGroups),
            'archived_groups' => GroupsListResource::collection($archivedGroups),
            'logs' => LogListResource::collection($user->logs()->latest()->take(25)->get())
        ]);
    }

    /**
     * Generate a signed URL for setting up password and return a response.
     *
     * @return Response
     */
    public function setupPassowrd()
    {
        $signedURL = URL::temporarySignedRoute(
                "password.set", 
                now()->addMinutes(30)
            );

        return Inertia::render("Auth/SetupPassword",[
                "signedURL" => $signedURL
            ]);
    }

    /**
     * Register a new user.
     *
     * @param RegisterUserRequest $request
     * @return void
     */
    public function registerUser(RegisterUserRequest $request)
    {
        $user = $this->usersService->registerUser($request);
    }

    /**
     * Set password for a user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function setPassword(Request $request)
    {
        $validated = $request->validate([
            "password" => "required|string|min:8|confirmed|max:255",
            "user" => "required|exists:users,id"
        ]);

        $user = $this->usersService->setPassword($request);

        return redirect()->route("profile.show");
    }

    // /**
    //  * Edit user information.
    //  *
    //  * @param User $user
    //  * @return JsonResponse
    //  */
    // public function edit(User $user)
    // {
    //     $userCollect = new User([
    //         "id" => $user->id,
    //         "first_name" => $user->first_name,
    //         "last_name" => $user->last_name,
    //         "email" => $user->email,
    //     ]);

    //     return response()->json($userCollect, JsonResponse::HTTP_OK);
    // }

    /**
     * Update user information.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     */

     public function update(Request $request,$id) {
        $client = $this->usersService->getByID($id);

        if(is_null($client)) {
            return abort(404);
        }

        try {

            $client = $this->usersService->update($request,$client);

            return redirect()->to('users');

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
    /**
     * Export users to Excel.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request) {
        $users = null;
        if($request->has('ids')) {
            $users = explode(',', $request->input('ids'));
        }

        return Excel::download(new UsersExport($users), "users-export.xlsx");
    }

    /**
     * Destroy (delete) a user.
     *
     * @param User $user
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(User $user, Request $request) {
        try {
            $userDeleted = $this->usersService->destroy($user);

            if (!$request->header("x-inertia")) {
                if ($userDeleted) {
                    return response()->json([
                        'message' => 'User deleted successfully'
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Failed to delete user'
                    ], 500);
                }
            }
        } catch (\Exception $e) {
            if (!$request->header("x-inertia")) {
                return response()->json([
                    'message' => 'Internal Server Error'
                ], 500);
            }
        }
    }

    /**
     * Reset the password for a user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            "password" => "required|string|min:8|confirmed|max:255",
            "user" => "required|exists:users,id",
        ]);
        

        $user = $this->usersService->resetPassword($validated);

        if ($user) {
            return response()->json([
                "message" => "User password got updated successfully."
            ], JsonResponse::HTTP_OK);
        }

        return response()->json([
            "message" => "Failed to update password for given user."
        ], JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * Permanently delete a user.
     *
     * @param int $id
     * @return JsonResponse|Response
     */
    public function delete($id) {
        try {
            $user = $this->usersService->getByID($id,true);
            if(!$user) {
                return abort(404);
            }

            $userDeleted = $this->usersService->forceDelete($user);

            if($userDeleted) {
                $this->logService->log([
                    'message' => 'User: '.$user->name.' has been deleted forever, the user had id: '.$id,
                    'context' => Log::LOG_CONTEXT_USERS,
                    'ttl'=> Log::LOG_TTL_FOREVER
                ]);
                return response()->json([
                    'message' => 'User was deleted successfully',
                ], JsonResponse::HTTP_OK);
            }

            return response()->json([
                "message" => "Failed to restore staff"
            ], JsonResponse::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error When Deleting User'
            ], 500);
        }
    }

}
