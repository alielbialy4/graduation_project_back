<?php

namespace App\Bll;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageUploaderRefac
{
    protected $basePath;

    public function __construct($basePath = null)
    {
        $this->basePath = $basePath;
    }

    public function upload(UploadedFile $file)
    {
        try {
            // Create directories if they don't exist
            File::makeDirectory($this->basePath, 0777, true, true);

            // Resize and save the original image
            Image::make($file)->save($this->basePath . 'web.' . $file->getClientOriginalExtension());
            // Create and save the thumbnail
            Image::make($file)
                ->resize(640, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($this->basePath . 'mobile.' . $file->getClientOriginalExtension());

            return 'web.' . $file->getClientOriginalExtension();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function moveUploadedFile($fileName, $destinationPath, $allowMobile = false)
    {
        $getFile = public_path('temp' . DIRECTORY_SEPARATOR . $fileName);
        $storagePath = public_path($fileName);

        if (file_exists($storagePath) && !file_exists($getFile)) {
            return $fileName;
        }
        if (!file_exists($getFile)) {
            return false;
        }

        File::makeDirectory($this->basePath, 0777, true, true);
        if ($allowMobile) {
            // Get the file extension
            $fileExtension = pathinfo($getFile, PATHINFO_EXTENSION);
            Image::make($getFile)->save($this->basePath . 'web.' . $fileExtension);
            Image::make($getFile)
                ->resize(640, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($this->basePath . 'mobile.' . $fileExtension);
            unlink($getFile); // Delete the original file after processing
            return 'web.' . pathinfo($getFile, PATHINFO_EXTENSION);
        }
        // check if destination path exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        // Move the file to the destination path
        rename($getFile, $destinationPath . $fileName);
        // remove every thing in $destinationPath before public
        $destinationPath = str_replace(public_path(), '', $destinationPath);
        return $destinationPath . $fileName;
    }

    public static function moveUploadedFileToStorage($fileName, $destinationPath)
    {
        // Define the path to the temp directory
        $getFile = public_path('temp' . DIRECTORY_SEPARATOR . $fileName);

        // Check if the file exists in the temp directory
        if (!file_exists($getFile)) {
            return false;
        }

        // Set the storage disk to 'private'
        $disk = Storage::disk('files');

        // Ensure the destination directory exists within the private disk
        $disk->makeDirectory($destinationPath);
        // Generate a random name with the original file extension
        $fileExtension = pathinfo($getFile, PATHINFO_EXTENSION);

        $randomFileName = Str::random(20) . '.' . $fileExtension;

        // Define the destination path on the private disk with the random file name
        $destinationFilePath = $destinationPath . '/' . $randomFileName;

        // Move the file to the private disk with a random name
        $disk->putFileAs($destinationPath, $getFile, $randomFileName);

        // Delete the original file from the public temp directory
        unlink($getFile);

        // Return the path of the file on the private disk
        return 'files' . DIRECTORY_SEPARATOR . $destinationFilePath;
    }
    public function getPrivateFileLink($filePath)
    {
        // Check if the file exists in the private storage
        if (Storage::disk('files')->exists($filePath)) {
            // Generate a temporary signed URL (e.g., valid for 2 hour)
            $path = storage_path('app/files/' . $filePath);
            return $path;
        }

        return false;
    }
}
