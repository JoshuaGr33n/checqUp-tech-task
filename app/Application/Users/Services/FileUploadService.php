<?php

namespace App\Application\Users\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Store the uploaded file.
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string $disk
     * @return string The stored file path
     */
    public function upload(UploadedFile $file, string $path = 'profiles', string $disk = 'public'): string
    {
        return $file->store($path, $disk);
    }

    /**
     * Delete a file from storage.
     *
     * @param string $filePath
     * @param string $disk
     * @return bool
     */
    public function delete(string $filePath, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->delete($filePath);
    }
}
