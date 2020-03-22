<?php

namespace App\Controllers;

use App\DTO\RegistrationDTO;
use App\Interfaces\HttpStatusCodes;
use App\Services\RegistrationService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rakit\Validation\Validator;

class RegistrationController extends ApiController
{
    private Validator $validator;
    private RegistrationService $registrationService;

    public function __construct(Validator $validator, RegistrationService $registrationService)
    {
        $this->validator = $validator;
        $this->registrationService = $registrationService;
    }

    public function register(Request $request, Response $response): Response
    {
        $requestBody = $request->getParsedBody();

        $registrationDTO = new RegistrationDTO(
            htmlspecialchars(strip_tags($requestBody['firstName'])),
            htmlspecialchars(strip_tags($requestBody['lastName'])),
            htmlspecialchars(strip_tags($requestBody['email'])),
            htmlspecialchars(strip_tags($requestBody['password']))
        );

        $validation = $this->validator->validate((array) $registrationDTO, [
            'firstName' => 'required|max:25',
            'lastName' => 'required|max:25',
            'email' => 'required|email|max:50',
            'password' => 'required|min:6|max:100',
        ]);

        if ($validation->fails()) {
            return $this->response($response, $validation->errors()->firstOfAll(), HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        $user = $this->registrationService->register($registrationDTO);

        return $this->response($response, $user, HttpStatusCodes::CREATED);
    }
}
