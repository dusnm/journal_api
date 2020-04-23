<?php

namespace App\Middleware;

use App\Interfaces\ErrorMessages;
use App\Interfaces\HttpStatusCodes;
use App\Services\JwtService;
use App\Traits\ResponseTrait;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use UnexpectedValueException;

class TokenDecodingMiddleware
{
    use ResponseTrait;

    private JwtService $jwtService;
    private Logger $log;

    public function __construct(JwtService $jwtService, Logger $log)
    {
        $this->jwtService = $jwtService;
        $this->log = $log;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $requestParams = $request->getQueryParams();
        $authorizationHeader = $request->getHeader('Authorization');

        $errorResponse = new Response();

        if (!isset($requestParams['token']) && empty($authorizationHeader)) {
            return $this->response($errorResponse, ['error' => ErrorMessages::UNAUTHORIZED], HttpStatusCodes::UNAUTHORIZED);
        }

        if (isset($requestParams['token']) && !empty($authorizationHeader)) {
            return $this->response($errorResponse, ['error' => ErrorMessages::DUAL_AUTHORIZATION_TYPE], HttpStatusCodes::BAD_REQUEST);
        }

        try {
            if (isset($requestParams['token'])) {
                $decodedData = $this->jwtService->decode($requestParams['token']);

                $request = $request->withAttribute('decodedData', $decodedData);

                return $handler->handle($request);
            }

            if (!empty($authorizationHeader)) {
                $authorizationString = $authorizationHeader[0];

                [$authorizationType, $token] = explode(' ', $authorizationString);

                if ('bearer' !== strtolower($authorizationType)) {
                    return $this->response($errorResponse, ['error' => ErrorMessages::INVALID_AUTHORIZATION_TYPE], HttpStatusCodes::UNAUTHORIZED);
                }

                $decodedData = $this->jwtService->decode($token);

                $request = $request->withAttribute('decodedData', $decodedData);

                return $handler->handle($request);
            }
        } catch (ExpiredException | BeforeValidException | SignatureInvalidException | UnexpectedValueException $e) {
            $this->log->warning($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
            ]);

            return $this->response($errorResponse, ['error' => ErrorMessages::UNAUTHORIZED], HttpStatusCodes::UNAUTHORIZED);
        }
    }
}
