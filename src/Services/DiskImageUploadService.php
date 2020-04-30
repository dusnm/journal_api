<?php

namespace App\Services;

use App\Interfaces\ImageUploadInterface;
use Psr\Http\Message\UploadedFileInterface;

use function App\Helpers\randomString;
use function App\Helpers\url;

define('UPLOAD_DIRECTORY', '/public/images/'.date('d-m-Y'));

class DiskImageUploadService implements ImageUploadInterface
{
    private string $uploadPath;

    public function __construct()
    {
        $this->uploadPath = __DIR__.'/../../'.UPLOAD_DIRECTORY;
    }

    /**
     * Generates a random file name for the uploaded file and moves the file to the upload directory
     *
     * @param UploadedFileInterface $uploadedFile
     *
     * @return string URL of the uploaded file
    */
    public function upload(UploadedFileInterface $uploadedFile): string
    {
        // Create a directory to store the uploaded file based on the current date
        if (!file_exists($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }

        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = randomString(15);
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $filePath = $this->uploadPath.DIRECTORY_SEPARATOR.$filename;

        $uploadedFile->moveTo($filePath);

        return url(str_replace('/public', '', UPLOAD_DIRECTORY).DIRECTORY_SEPARATOR.$filename);
    }
}
