<?php

namespace App\Services;

use App\Interfaces\ImageUploadInterface;
use Psr\Http\Message\UploadedFileInterface;

use function App\Helpers\randomString;
use function App\Helpers\url;

class DiskImageUploadService implements ImageUploadInterface
{
    /**
     * Generates a random file name for the uploaded file and moves the file to the upload directory
     *
     * @param UploadedFileInterface $uploadedFile
     *
     * @return string URL of the uploaded file
    */
    public function upload(UploadedFileInterface $uploadedFile): string
    {
        $uploadPath =  __DIR__.'/../../public/images/'.date('d-m-Y');

        // Create a directory to store the uploaded file based on the current date
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = randomString(15);
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $filePath = $uploadPath.DIRECTORY_SEPARATOR.$filename;

        $uploadedFile->moveTo($filePath);
    
        return url(strstr($filePath, '/images'));
    }
}
