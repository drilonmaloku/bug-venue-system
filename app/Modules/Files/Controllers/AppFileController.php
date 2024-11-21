<?php

namespace App\Modules\Files\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AppFileController extends Controller
{

    public function getFile($path)
    {
     
//        if (!Auth::check()) {
//            abort(403, 'Unauthorized access.');
//        }

        if (Storage::disk('private')->exists($path)) {
            return Storage::disk('private')->download($path);
        } else {
            abort(404, 'File not found.');
        }
    }

}
