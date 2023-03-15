<?php

namespace App\Traits;

use Plank\Mediable\Facades\MediaUploader;

trait Uploads
{

    public function getUploadedFilePath($file, $folder = 'settings')
    {
        $path = '';

        if (!$file || !$file->isValid()) {
            return $path;
        }

        $file_name = $file->getClientOriginalName();

        // Upload file
        $file->storeAs($folder, $file_name);

        // Prepare db path
        $path = $folder . '/' . $file_name;

        return $path;
    }

    public function getMedia($file, $folder = 'settings')
    {
        $path = '';

        if (!$file || !$file->isValid()) {
            return $path;
        }

        $path = $folder;

        return MediaUploader::fromSource($file)->toDirectory($path)->upload();
    }

    public function importMedia($file, $folder = 'settings', $disk = null)
    {
        $path = '';

        if (!$disk) {
            $disk = config('mediable.default_disk');
        }

        $path = $folder . '/' . basename($file);

        return MediaUploader::importPath($disk, $path);
    }
}
