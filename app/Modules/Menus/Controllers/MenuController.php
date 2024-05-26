<?php namespace App\Modules\Menus\Controllers;

use App\Modules\Clients\Exports\ClientsExport;
use App\Modules\Clients\Exports\ContactPersonsExport;
use App\Modules\Clients\Imports\ClientsExcelImport;
use App\Modules\Clients\Requests\AddClientContactPersonRequest;
use App\Modules\Clients\Requests\AddClientRequest;
use App\Modules\Clients\Requests\UpdateClientContactPersonRequest;
use App\Modules\Clients\Requests\UpdateClientRequest;
use App\Modules\Clients\Resources\ClientViewResource;
use App\Modules\Clients\Resources\ContactPersonResource;
use App\Modules\Clients\Services\ClientsContactPersonsService;

use App\Modules\Clients\Services\ClientsService;
use App\Modules\Menus\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use App\Modules\Clients\Services\VenuesService;
use App\Modules\Clients\Resources\ClientListResource;

class MenuController extends Controller
{
    private $menuService;
    private $logService;

    public function __construct(
        MenuService $menuService,
        LogService $logService
    )
    {
        $this->menuService = $menuService;
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $menus = $this->menuService->getAll($request);

        return view('pages/menus/index',[
            'is_on_search'=>count($request->all()),
            'menus'=>$menus
        ]);
    }

    public function create()
    {
        return view('pages/menus/create');
    }

    public function store(Request $request) {
        $menu = $this->menuService->store($request);

        return redirect()->to('menus');
    }

    public function view($id)
    {
        $menu = $this->menuService->getByID($id);
        if(is_null($menu)) {
            return abort(404);
        }
        return view('pages/menus/show',[
            'menu'=>$menu
        ]);
    }

    public function edit($id)
    {
        $menu = $this->menuService->getByID($id);
        if(is_null($menu)) {
            return abort(404);
        }
        return view('pages/menus/edit',[
            'menu'=>$menu
        ]);
    }

    public function update(Request $request,$id) {
        $menu = $this->menuService->getByID($id);

        if(is_null($menu)) {
            return abort(404);
        }
        $client = $this->menuService->update($request,$menu);

        return redirect()->back();
    }

    public function delete($id){
        $menu = $this->menuService->getByID($id);
        if(is_null($menu)) {
            abort('Venue not found',404);
        }
        $menuDeleted = $this->menuService->delete($menu);
        return redirect()->to('menus');
    }

}
