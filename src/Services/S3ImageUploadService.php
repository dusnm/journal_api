<?php

namespace App\Services;

use App\Interfaces\ImageUploadInterface;
use Psr\Http\Message\UploadedFileInterface;

class S3ImageUploadService implements ImageUploadInterface
{
    /**
     * Generates a random file name and uploads the file to the S3 bucket
     *
     * @param UploadedFileInterface $uploadedFile
     *
     * @return string URL of the uploaded file
     *
    */
    public function upload(UploadedFileInterface $uploadedFile): string
    {
        //TODO: Implement this method
        return '';
    }
}
