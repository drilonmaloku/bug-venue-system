<?php namespace App\Modules\Location\Controllers;

use App\Modules\Users\Models\LocationUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use App\Modules\Location\Services\LocationServices;
use App\Modules\Logs\Models\Log;
use App\Modules\Users\Requests\CreateUserRequest;
use App\Modules\Users\Services\UsersService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    private $locationServices;
    private $logService;
    private $usersService;

    public function __construct(
        LocationServices $locationServices,
        LogService $logService,
        UsersService $usersService
    )
    {
        $this->locationServices = $locationServices;
        $this->usersService = $usersService;
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $locations = $this->locationServices->getAll($request);

        if(session('success_message')){
            Alert::success('Success!', session('success_message'));
        }

        return view('pages/locations/index',[
            'is_on_search'=>count($request->all()),
            'locations'=>$locations
        ]);
    }

    public function view($id)
    {
        $location = $this->locationServices->getByID($id);
        if(is_null($location)) {
            return abort(404);
        }
        return view('pages/locations/show',[
            'location'=>$location
        ]);
    }

    public function create()
    {
        return view('pages/locations/create');
    }

    public function store(Request $request) {
        $userData = [
            "username" => $request->input("username"),
            "first_name" => $request->input("first_name"),
            "last_name" => $request->input("last_name"),
            "email" => $request->input("email"),
            "phone" => $request->input("phone"),
            "password" => $request->input("password"),
            "role" => $request->input("role")
        ];
    
        $user = $this->usersService->store(new CreateUserRequest($userData));
    
        if ($user) {
            $this->logService->log([
                'message' => 'Useri i ri është krijuar me ID: '.$user->id,
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl' => Log::LOG_TTL_SIX_MONTHS
            ]);
    
            $locationData = [
                'name' => $request->input('name'),
                'owner_id' => $user->id,
                'uuid'=>  Str::uuid(),
            ];
    
           $location = $this->locationServices->store($locationData);
            if ($location) {
                $locationUserData = [
                    'user_id' => $user->id,
                    'location_id' => $location->id,
                ];

                LocationUser::create($locationUserData);
            }
            return redirect()->to('locations')->withSuccessMessage('Location u krijua me sukses');
        }
    
        return redirect()->back()->withErrorMessage('Ndodhi një gabim gjatë krijimit të përdoruesit.');
    }

    public function edit($id)
    {
        $location = $this->locationServices->getByID($id);
        if(is_null($location)) {
            return abort(404);
        }
        return view('pages/locations/edit',[
            'location'=>$location
        ]);
    }

    public function update(Request $request,$id) {
        $location = $this->locationServices->getByID($id);

        if(is_null($location)) {
            return abort(404);
        }

        try {

            $client = $this->locationServices->update($request,$location);

            return redirect()->to('locations')->withSuccessMessage('Location u be update me sukses');

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

}
