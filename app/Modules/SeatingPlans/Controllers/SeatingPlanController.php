<?php

namespace App\Modules\SeatingPlans\Controllers;

use App\Modules\SeatingPlans\Services\SeatingPlanService;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class SeatingPlanController extends Controller
{
    private $seatingPlanService;
    private $logService;

    public function __construct(
        SeatingPlanService $seatingPlanService,
        LogService $logService
    )
    {
        $this->seatingPlanService = $seatingPlanService;
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $seatingPlans = $this->seatingPlanService->getAll($request);
      
        if(session('success_message')){
            Alert::success('Success!', session('success_message'));
        }
        return view('pages/seating-plans/index',[
            'is_on_search'=>count($request->all()),
            'seatingPlans'=>$seatingPlans
        ]);
    }

    public function create()
    {
        return view('pages/seating-plans/create');
    }

    public function store(Request $request) {
        $seatingPlan = $this->seatingPlanService->store($request);

        return redirect()->to('seating-plans')
            ->withSuccessMessage('Plani i ulseve u shtua me sukses');
    }

    public function view($id)
    {
        $seatingPlan = $this->seatingPlanService->getByID($id);
        if(is_null($seatingPlan)) {
            return abort(404);
        }
        return view('pages/seating-plans/show',[
            'seatingPlan'=>$seatingPlan
        ]);
    }

    public function edit($id)
    {
        $seatingPlan = $this->seatingPlanService->getByID($id);
        if(is_null($seatingPlan)) {
            return abort(404);
        }
        return view('pages/seating-plans/edit',[
            'seatingPlan'=>$seatingPlan
        ]);
    }

    public function update(Request $request,$id) {
        $seatingPlan = $this->seatingPlanService->getByID($id);

        if(is_null($seatingPlan)) {
            return abort(404);
        }
        $seatingPlan = $this->seatingPlanService->update($request,$seatingPlan);
        return redirect()->to('seating-plans')->withSuccessMessage('Plani i ulseve u be update me sukses');
    }

    public function delete($id){
        $seatingPlan = $this->seatingPlanService->getByID($id);
        if(is_null($seatingPlan)) {
            abort('Seating plan not found',404);
        }
        $seatingPlanDeleted = $this->seatingPlanService->delete($seatingPlan);
        return redirect()->to('seating-plans')->withSuccessMessage('Plani i ulseve u be delete');
    }
} 