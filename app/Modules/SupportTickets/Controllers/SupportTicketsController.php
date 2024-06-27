<?php 


namespace App\Modules\SupportTickets\Controllers;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use App\Modules\SupportTickets\Resources\SupportTicketsListCommentResource;
use App\Modules\SupportTickets\Services\FileService;
use App\Modules\SupportTickets\Services\SupportTicketsCommentServices;
use App\Modules\SupportTickets\Services\SupportTicketsService;
use Illuminate\Http\JsonResponse;

class SupportTicketsController extends Controller
{
    private $supportTicketsService;
    private $commentSupportTicketService;

    public function __construct(
        SupportTicketsService $supportTicketsService,
        SupportTicketsCommentServices $commentSupportTicketService
    )
    {
        $this->supportTicketsService = $supportTicketsService;
        $this->commentSupportTicketService = $commentSupportTicketService;
    }

    public function index(Request $request)
    {
        $supportTickets = $this->supportTicketsService->getAll($request);


      
        return view('pages/supports/index',[
            'is_on_search'=>count($request->all()),
            'supportTickets'=>$supportTickets
        ]);
    }



    public function create()
    {
        return view('pages/supports/create');
    }



    public function store(Request $request) {
        $fileService = app(FileService::class);
     

        $fileData = null;
        if($request->file('attachment')) {
            $fileService = app(FileService::class);
            $fileData = $fileService->storeFile($request->file('attachment'),auth()->user()->id);
        }

        $ticketData = [
            'user_id' => auth()->user()->id,
            'resolver_id' => auth()->user()->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            "attachment" => !is_null($fileData) ? json_encode($fileData) : null,

        ];

        
        $this->supportTicketsService->store($ticketData);

     
        return redirect()->to('supports-tickets')->withSuccessMessage('Tiketa u krijua me sukses');
    }



    public function view($id)
    {
        $ticket = $this->supportTicketsService->getByID($id);
        if(is_null($ticket)) {
            return abort(404);
        }
        $attachment = json_decode($ticket->attachment, true);
        $filePath = $attachment['file_path'] ?? null;
    
        return view('pages/supports/show',[
            'ticket'=>$ticket,
            'filePath'=>$filePath,

        ]);
    }



    public function storeComment(Request $request, $id)
    {
        $ticket = $this->supportTicketsService->getByID($id);
        $ticketComment = $this->commentSupportTicketService->storeComment($request, $ticket);
        if ($ticketComment) {

            return redirect()->back()->withSuccessMessage('Komenti per rezervim u shtua me sukses');
        }

        return response()->json([
            "message" => "Failed to create new Comment."
        ], JsonResponse::HTTP_BAD_REQUEST);




        try {
            $ticket = $this->supportTicketsService->getByID($id);
            $ticketComment = $this->commentSupportTicketService->storeComment($request, $ticket);
            if ($ticketComment) {

                return response()->json([
                    'message' => 'Comment was created successfully',
                    'data' => SupportTicketsListCommentResource::make($ticketComment)
                ], JsonResponse::HTTP_OK);
            }

            return response()->json([
                "message" => "Failed to create new Comment."
            ], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }



 
    public function updateStatus(Request $request, $id)
    {
        $ticket = $this->supportTicketsService->getByID($id);

        if (is_null($ticket)) {
            return abort(404);
        }

        try {
            $ticketStatus = $this->supportTicketsService->update($request, $ticket);
            if ($ticketStatus) {
                return redirect()->back()->withSuccessMessage('Statusi u ndryshua me sukses');
            }

            return response()->json([
                "message" => "Failed to update status."
            ], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }



    public function updateStatusOpen(Request $request, $id)
    {
        $ticket = $this->supportTicketsService->getByID($id);

        if (is_null($ticket)) {
            return abort(404);
        }

        try {
            $ticketStatus = $this->supportTicketsService->updateStatusOpen($request, $ticket);
            if ($ticketStatus) {
                return redirect()->back()->withSuccessMessage('Statusi u ndryshua me sukses');
            }

            return response()->json([
                "message" => "Failed to update status."
            ], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}
