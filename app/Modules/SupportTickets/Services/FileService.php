<?php

declare(strict_types=1);

namespace App\Modules\SupportTickets\Services;


use Illuminate\Support\Facades\Storage;

class FileService
{

    /**
     * Store a new file.
     *
     */
    public function storeFile($file, $userId)
    {

        $originalName = $file->getClientOriginalName();
        $fileName = $originalName;
        $filePath = "files/{$userId}/{$originalName}";

        $count = 1;

        while (Storage::disk('local')->exists($filePath)) {
            $count++;
            $fileName = pathinfo($originalName, PATHINFO_FILENAME) . "-{$count}." . $file->getClientOriginalExtension();
            $filePath = "files/{$userId}/{$fileName}";
        }

        $filePath = $file->storeAs("files/{$userId}", $fileName, 'local');
        return [
            "original_file_name" => $fileName,
            "file_path" => $filePath,
        ];
    }


    /**
     * Delete a file.
     *
     * @param string $filePath The path of the file to delete.
     * @return bool True if the file deletion was successful, false otherwise.
     */
    public function deleteFile(string $filePath)
    {
        if (Storage::disk('local')->exists($filePath)) {
            Storage::disk('local')->delete($filePath);
            return true; // File deletion successful
        }
        return false; // File not found or deletion failed
    }


        /**
     * Store or update a file.
     *
     * @param mixed $file The file to store or update.
     * @param int $userId The ID of the user associated with the file.
     * @param string|null $existingFilePath The existing file path if updating, null otherwise.
     * @return array An array containing the original file name and the new file path.
     */
    public function storeOrUpdateFile($file, int $userId, ?string $existingFilePath = null)
    {
        // If an existing file path is provided, delete the existing file
        if ($existingFilePath) {
            $this->deleteFile($existingFilePath);
        }

        $originalName = $file->getClientOriginalName();
        $fileName = $originalName;
        $filePath = "staff-files/user/{$userId}/{$originalName}";

        $count = 1;

        // Check if the file path already exists, if so, append a number to make it unique
        while (Storage::disk('local')->exists($filePath)) {
            $count++;
            $fileName = pathinfo($originalName, PATHINFO_FILENAME) . "-{$count}." . $file->getClientOriginalExtension();
            $filePath = "staff-files/user/{$userId}/{$fileName}";
        }

        // Store the new file
        $filePath = $file->storeAs("staff-files/user/{$userId}", $fileName, 'local');
        return [
            "original_file_name" => $fileName,
            "file_path" => $filePath,
        ];
    }
}
