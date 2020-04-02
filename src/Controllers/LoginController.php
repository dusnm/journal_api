<?php

namespace App\Controllers;

use App\DTO\User\LoginDTO;
use App\Exceptions\UserNotVerifiedException;
use App\Interfaces\ErrorMessages;
use App\Interfaces\HttpStatusCodes;
use App\Models\User;
use App\Services\JwtService;
use App\Services\LoginService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Rakit\Validation\Validator;

class LoginController extends ApiController
{
    private Validator $validator;
    private LoginService $loginService;
    private Logger $log;
    private JwtService $jwtService;

    public function __construct(Validator $validator, LoginService $loginService, Logger $log, JwtService $jwtService)
    {
        $this->validator = $validator;
        $this->loginService = $loginService;
        $this->log = $log;
        $this->jwtService = $jwtService;
    }

    public function login(Request $request, Response $response): Response
    {
        $requestBody = $request->getParsedBody();

        $loginDTO = new LoginDTO(
            htmlspecialchars(strip_tags($requestBody['email'])),
            htmlspecialchars(strip_tags($requestBody['password']))
        );

        $validation = $this->validator->validate((array) $loginDTO, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->response($response, $validation->errors()->firstOfAll(), HttpStatusCodes::UNPROCESSABLE_ENTITY);
        }

        try {
            $userOrNull = $this->loginService->login($loginDTO);

            if (!($userOrNull instanceof User)) {
                return $this->response($response, ['error' => ErrorMessages::LOGIN_FAILED], HttpStatusCodes::UNAUTHORIZED);
            }

            $token = $this->jwtService->sign($userOrNull->toArray(), 60 * 60 * 24);

            return $this->response($response, ['token' => $token], HttpStatusCodes::OK);
        } catch (ModelNotFoundException $e) {
            return $this->response($response, ['error' => ErrorMessages::LOGIN_FAILED], HttpStatusCodes::UNAUTHORIZED);
        } catch (UserNotVerifiedException $e) {
            return $this->response($response, ['error' => $e->getMessage()], HttpStatusCodes::UNAUTHORIZED);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }
}
