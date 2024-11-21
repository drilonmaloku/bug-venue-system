<?php

namespace App\Modules\Decors\Controllers;

use App\Modules\Decors\Services\DecorService;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class DecorController extends Controller
{
    private $decorService;
    private $logService;

    public function __construct(
        DecorService $decorService,
        LogService $logService
    )
    {
        $this->decorService = $decorService;
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $decors = $this->decorService->getAll($request);
      
        if(session('success_message')){
            Alert::success('Success!', session('success_message'));
        }
        return view('pages/decors/index',[
            'is_on_search'=>count($request->all()),
            'decors'=>$decors
        ]);
    }

    public function create()
    {
        return view('pages/decors/create');
    }

    public function store(Request $request) {
        $decors = $this->decorService->store($request);

        return redirect()->to('decors')
            ->withSuccessMessage('Dekori u shtua me sukses');

    }

    public function view($id)
    {
        $decor = $this->decorService->getByID($id);
        if(is_null($decor)) {
            return abort(404);
        }
        return view('pages/decors/show',[
            'decor'=>$decor
        ]);
    }

    public function edit($id)
    {
        $decors = $this->decorService->getByID($id);
        if(is_null($decors)) {
            return abort(404);
        }
        return view('pages/decors/edit',[
            'decor'=>$decors
        ]);
    }

    public function update(Request $request,$id) {
        $decors = $this->decorService->getByID($id);

        if(is_null($decors)) {
            return abort(404);
        }
        $client = $this->decorService->update($request,$decors);
        return redirect()->to('decors')->withSuccessMessage('Dekori u be update me sukses');
    }

    public function delete($id){
        $decors = $this->decorService->getByID($id);
        if(is_null($decors)) {
            abort('Decor not found',404);
        }
        $decorDeleted = $this->decorService->delete($decors);
        return redirect()->to('decors')->withSuccessMessage('Dekori u be delete');
    }

}
