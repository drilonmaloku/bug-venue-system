<?php

namespace App\Modules\Collaborators\Controllers;

use App\Modules\collaborators\Services\CollaboratorsService;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class CollaboratorsController extends Controller
{
    private $collaboratorService;
    private $logService;

    public function __construct(
        CollaboratorsService $collaboratorService,
        LogService $logService
    )
    {
        $this->collaboratorService = $collaboratorService;
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $collaborators = $this->collaboratorService->getAll($request);
      
        if(session('success_message')){
            Alert::success('Success!', session('success_message'));
        }
        return view('pages/collaborators/index',[
            'is_on_search'=>count($request->all()),
            'collaborators'=>$collaborators
        ]);
    }

    public function create()
    {
        return view('pages/collaborators/create');
    }

    public function store(Request $request) {
        $collaborators = $this->collaboratorService->store($request);

        return redirect()->to('collaborators')
            ->withSuccessMessage('Bashkpuntori u shtua me sukses');

    }

    public function view($id)
    {
        $collaborators = $this->collaboratorService->getByID($id);
        if(is_null($collaborators)) {
            return abort(404);
        }
        return view('pages/collaborators/show',[
            'collaborator'=>$collaborators
        ]);
    }

    public function edit($id)
    {
        $collaborators = $this->collaboratorService->getByID($id);
        if(is_null($collaborators)) {
            return abort(404);
        }
        return view('pages/collaborators/edit',[
            'collaborator'=>$collaborators
        ]);
    }

    public function update(Request $request,$id) {
        $collaborators = $this->collaboratorService->getByID($id);

        if(is_null($collaborators)) {
            return abort(404);
        }
        $client = $this->collaboratorService->update($request,$collaborators);
        return redirect()->to('collaborators')->withSuccessMessage('Bashkpuntori u be update me sukses');
    }

    public function delete($id){
        $collaborators = $this->collaboratorService->getByID($id);
        if(is_null($collaborators)) {
            abort('Collaborator not found',404);
        }
        $collaboratorDeleted = $this->collaboratorService->delete($collaborators);
        return redirect()->to('collaborators')->withSuccessMessage('Bashkpuntori u be delete');
    }

}
