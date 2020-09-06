<?php
/*
 * Contract for classes concerning file upload
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
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
