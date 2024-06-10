<?php namespace App\Modules\Invoices\Controllers;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Invoices\Services\InvoicesServices;
use App\Modules\Logs\Services\LogService;

use RealRashid\SweetAlert\Facades\Alert;

class InvoicesController extends Controller
{
    private $invoiceServices;
    private $logService;

    public function __construct(
        InvoicesServices $invoiceServices,
        LogService $logService
    )
    {
        $this->invoiceServices = $invoiceServices;
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $expenses = $this->invoiceServices->getAll($request);

        if(session('success_message')){
            Alert::success('Success!', session('success_message'));
        }

        // return view('pages/expenses/index',[
        //     'is_on_search'=>count($request->all()),
        //     'expenses'=>$expenses
        // ]);
    }

    public function view($id)
    {
        $invoice = $this->invoiceServices->getByID($id);
        if(is_null($invoice)) {
            return abort(404);
        }
        return view('pages/expenses/show',[
            'expense'=>$invoice
        ]);
    }



    public function create()
    {
        return view('pages/expenses/create');
    }


    public function store(Request $request) {
        $expenseData = [
            'user_id' => auth()->user()->id,
            'date' => $request->input('date'),
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
        ];

        $client = $this->expenseServices->store($expenseData);

     
        return redirect()->to('expenses')->withSuccessMessage('Shpenzimi u krijua me sukses');
    }








    public function edit($id)
    {
        $expense = $this->expenseServices->getByID($id);
        if(is_null($expense)) {
            return abort(404);
        }
        return view('pages/expenses/edit',[
            'expense'=>$expense
        ]);
    }

    public function update(Request $request,$id) {
        $expense = $this->expenseServices->getByID($id);

        if(is_null($expense)) {
            return abort(404);
        }

        try {

            $client = $this->expenseServices->update($request,$expense);

            return redirect()->to('expenses')->withSuccessMessage('Shpenzimi u be update me sukses');

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }




    
    /**
     * Destroy (delete) a user.
     *
     * @param User $user
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $expense = $this->expenseServices->getByID($id);
        $expenseDeleted = $this->expenseServices->destroy($expense);

        if ($expenseDeleted) {
            return redirect()->to('expenses')->withSuccessMessage('Perduruesi u fshi me sukses');
        } else {
            return response()->json([
                'message' => 'Failed to delete user'
            ], 500);
        }
    }

}
