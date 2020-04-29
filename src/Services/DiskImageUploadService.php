<?php

namespace App\Services;

use App\Interfaces\ImageUploadInterface;
use Psr\Http\Message\UploadedFileInterface;

class DiskImageUploadService implements ImageUploadInterface
{
    private const UPLOAD_DIRECTORY = __DIR__.'/../../public/images/'.date('d-m-Y');

    /**
     * Generates a random file name for the uploaded file and moves the file to the upload directory
     *
     * @param UploadedFileInterface $uploadedFile
     *
     * @return string File path of the uploaded file
    */
    public function upload(UploadedFileInterface $uploadedFile): string
    {
        // Create a directory to store the uploaded file based on the current date
        if (!file_exists(self::UPLOAD_DIRECTORY)) {
            mkdir(self::UPLOAD_DIRECTORY, 0775, true);
        }

        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $filePath = self::UPLOAD_DIRECTORY.DIRECTORY_SEPARATOR.$filename;

        $uploadedFile->moveTo($filePath);

        return $filePath;
    }
}
