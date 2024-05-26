<?php namespace App\Modules\Menus\Services;

use App\Modules\Clients\Models\Client;
use App\Modules\Menus\Models\Menu;
use Illuminate\Http\Request;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;

class MenuService
{
    private $logService;

    public function __construct()
    {
        $this->logService = new LogService();
    }

    /**
     * Gets the list of clients
     **/
    public function getAll(Request $request){
        $perPage = $request->has('per_page') ? $request->input('per_page') : 10;
        $query = Menu::query();

        if ($request && $request->has("search") && !empty($request->input("search"))) {
            $searchTerm = '%' . $request->input("search") . '%';

            $query->where(function ($subquery) use ($searchTerm) {
                $subquery->where('name', 'LIKE', $searchTerm)
                    ->orWhere('description', 'LIKE', $searchTerm)
                    ->orWhere('price', 'LIKE', $searchTerm);
            });
        }
        $query->orderBy('updated_at', 'desc');
        return $query->paginate($perPage);

    }

    /**
     * Get Client by ID
     * @param int|array $id
     **/
    public function getByID($id){
        return Menu::find($id);
    }

    
    /**
     * Get Client
     **/
    public function getBasicList(){
        return Client::select('id', 'name')->get();
    }

    /**
     * Get Clients by ID
     * @param int|array $id
     **/
    public function getByIds($ids){
        return Client::whereIn('id', $ids)->get();
    }

    /**
     * Stores new Client
     **/
    public function store($request)
    {
        $menu = Menu::create([
            "name" => $request->input('name'),
            "price" => $request->input('price'),
            "description" => $request->input('description'),
        ]);

        if($menu){
            $this->logService->log([
                'message' => 'Menu is created succesfully',
                'context' => Log::LOG_CONTEXT_CLIENTS,
                'ttl'=> Log::LOG_TTL_THREE_MONTHS,
            ]);
        }

        return $menu;
    }

    /**
     * Updates existing client
     **/
    public function update($request, Menu $menu) {
        $menu->name = $request->input('name');
        $menu->price = $request->input('price');
        $menu->description = $request->input('description');
        $menuUpdated = $menu->save();


        return $menuUpdated;
    }

    /**
     * Deletes existing menu
     **/
    public function delete(Menu $menu) {
         $previousData = $menu->attributesToArray();
         $menuDeleted = $menu->delete();

        return $menuDeleted;
    }

}
