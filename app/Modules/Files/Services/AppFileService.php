<?php

namespace App\Modules\Files\Services;

use App\Modules\Files\Models\AppFile;
use Illuminate\Support\Facades\Storage;

class AppFileService
{
    public function store($file,$directory = 'files'){

        if(!$file) {
            return false;
        }
        $path = $file->store($directory, 'private');

        $createdFile = AppFile::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return $createdFile;
    }

    public function delete($id)
    {
        // Find the file in the database by its ID
        $file = AppFile::findOrFail($id);

        // Delete the file from the storage
        if (Storage::disk('private')->exists($file->file_path)) {
            Storage::disk('private')->delete($file->file_path);
        }

        // Delete the file's record from the database
        $file->delete();

        return response()->json(['message' => 'File deleted successfully'], 200);
    }
}
