<?php

namespace App\Controllers;

use App\Interfaces\ErrorMessages;
use App\Interfaces\HttpStatusCodes;
use App\Services\EmailVerificationService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmailVerificationController extends ApiController
{
    private Logger $log;
    private EmailVerificationService $emailVerificationService;

    public function __construct(Logger $log, EmailVerificationService $emailVerificationService)
    {
        $this->log = $log;
        $this->emailVerificationService = $emailVerificationService;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function verify(Request $request, Response $response): Response
    {
        try {
            $error = $request->getAttribute('error');

            if (isset($error)) {
                return $this->response($response, ['error' => $error], HttpStatusCodes::UNAUTHORIZED);
            }

            $decodedData = $request->getAttribute('decodedData');

            if (!$this->emailVerificationService->verify($decodedData->email)) {
                $this->log->error('Unknown error while verifying the user.', [
                    'route' => $request->getUri()->getPath(),
                ]);

                return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
            }

            return $this->response($response, ['verified' => true], HttpStatusCodes::OK);
        } catch (ModelNotFoundException $e) {
            return $this->response($response, ['error' => ErrorMessages::NOT_FOUND], HttpStatusCodes::NOT_FOUND);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
            ]);

            return $this->response($response, ['error' => ErrorMessages::SERVER_ERROR], HttpStatusCodes::INTERNAL_SERVER_ERROR);
        }
    }
}
