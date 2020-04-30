<?php

namespace App\Controllers;

use App\Interfaces\ErrorMessages;
use App\Interfaces\HttpStatusCodes;
use App\Interfaces\ImageUploadInterface;
use Monolog\Logger;
use Rakit\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ImageUploadController extends ApiController
{
    private ImageUploadInterface $imageUploadService;
    private Validator $validator;
    private Logger $log;

    public function __construct(ImageUploadInterface $imageUploadService, Validator $validator, Logger $log)
    {
        $this->imageUploadService = $imageUploadService;
        $this->validator = $validator;
        $this->log = $log;
    }

    public function uploadUserAvatar(Request $request, Response $response): Response
    {
        $decodedUser = $request->getAttribute('decodedData');

        if (!isset($decodedUser)) {
            return $this->response($response, ['error' => ErrorMessages::UNAUTHORIZED], HttpStatusCodes::UNAUTHORIZED);
        }

        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['image'];

        if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
            $this->log->error('Error uploading file, error code: '.$uploadedFile->getError(), [
                'route' => $request->getUri()->getPath(),
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }

        $validation = $this->validator->validate($_FILES, [
            'image' => 'required|uploaded_file:0,2M,jpeg,gif,png'
        ]);

        if ($validation->fails()) {
            return $this->response($response, ['error' => $validation->errors()->firstOfAll()], HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        $uploadedFileUrl = $this->imageUploadService->upload($uploadedFile);

        return $this->response($response, ['url' => $uploadedFileUrl], HttpStatusCodes::OK);
    }
}
