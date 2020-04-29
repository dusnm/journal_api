<?php

namespace App\Interfaces;

use Psr\Http\Message\UploadedFileInterface;

interface ImageUploadInterface
{
    /**
     * @param UploadedFileInterface $uploadedFile
     *
     * @return string
    */
    public function upload(UploadedFileInterface $uploadedFile): string;
}
