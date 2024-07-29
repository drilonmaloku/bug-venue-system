<?php

namespace App\Modules\Payments\Controllers;

use App\Modules\Clients\Services\ClientsService;
use App\Modules\Payments\Services\PaymentsService;
use App\Modules\Venues\Services\VenuesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use App\Modules\Logs\Models\Log;
use App\Modules\Payments\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class PaymentsController extends Controller
{
    private $venuesService;
    private $logService;
    private $clientsService;
    private $paymentsService;

    public function __construct(
        VenuesService $venuesService,
        LogService $logService,
        ClientsService $clientsService,
        PaymentsService $paymentsService
    ) {
        $this->paymentsService = $paymentsService;
        $this->venuesService = $venuesService;
        $this->logService = $logService;
        $this->clientsService = $clientsService;
    }

    public function index(Request $request)
    {
        $payments = $this->paymentsService->getAll($request);
        if (session('success_message')) {
            Alert::success('Success!', session('success_message'));
        }

        return view('pages/payments/index', [
            'payments' => $payments,
            'is_on_search' => count($request->all()),
        ]);
    }

    public function create()
    {
        return view('pages/reservations/create', [
            'venues' => $this->venuesService->getVenues()
        ]);
    }

    public function view($id)
    {

        $payment = $this->paymentsService->getByID($id);
        if (is_null($payment)) {
            return abort(404);
        }

        return view('pages/payments/show', [
            'payment' => $payment
        ]);
    }

    public function edit($id)
    {
        $payment = $this->paymentsService->getByID($id);
        if (is_null($payment)) {
            return abort(404);
        }
        return view('pages/payments/edit', [
            'payment' => $payment
        ]);
    }

    public function store(Request $request)
    {
        $clientData = [
            'name' => $request->input('client_name'),
            'email' => $request->input('client_email'),
            'phone_number' => $request->input('client_phone_number'),
            'additional_phone_number' => $request->input('client_additional_phone_number')
        ];

        $client = $this->clientsService->store($clientData);

        $venue = $this->venuesService->store($request);

        return redirect()->to('payments')->withSuccessMessage('Pagesa u krijua me sukses');
        try {
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $payment = $this->paymentsService->getByID($id);


        if (is_null($payment)) {
            return response()->json([
                'message' => 'Payment Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        try {

            $payment = $this->paymentsService->update($request, $payment);
            return redirect()->to('payments')->withSuccessMessage('Pagesa u be update me sukses');

            return response()->json([
                "message" => "Failed to update existing Payment."
            ], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function delete($id)
    {
        $payment = $this->paymentsService->getByID($id);
        if (is_null($payment)) {
            return response()->json([
                'message' => 'Payment Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        try {

            $reservation = $payment->reservation;

            $paymentDeleted = $this->paymentsService->delete($payment);
            $previousData = $payment->attributesToArray();
            if ($paymentDeleted) {

                $newCurrentPayment = $reservation->payments()->sum('value');

                $reservation->current_payment = $newCurrentPayment;
                $reservation->save();

                $this->logService->log([
                    'message' => 'Pagesa është fshire me sukses',
                    'context' => Log::LOG_CONTEXT_PAYMENTS,
                    'ttl'=> Log::LOG_TTL_THREE_MONTHS,
                    'previous_data'=> json_encode($previousData)
                ]);
                return  redirect()->to('payments')->withSuccessMessage('Pagesa u fshi me sukses');
            }

            return response()->json([
                "message" => "Failed to delete existing Payment."
            ], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }


    public function export(Request $request)
    {
        $payments = null;

        if($request->has('ids')) {
            $payments = explode(',', $request->input('ids'));
        }
        $this->logService->log([
            'message' => 'Payments are being exported to Excel',
            'context' => Log::LOG_CONTEXT_MENU,
            'ttl'=> Log::LOG_TTL_THREE_MONTHS,
        ]);
        return Excel::download(new PaymentsExport($payments), "payments-export.xlsx");
    }

}
