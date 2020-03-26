<?php

namespace App\Middleware;

use App\Interfaces\ErrorMessages;
use App\Services\JwtService;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use UnexpectedValueException;

class TokenDecodingMiddleware
{
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

        if (!isset($requestParams['token']) && empty($authorizationHeader)) {
            $request = $request->withAttribute('error', ErrorMessages::UNAUTHORIZED);

            return $handler->handle($request);
        }

        if (isset($requestParams['token']) && !empty($authorizationHeader)) {
            $request = $request->withAttribute('error', ErrorMessages::DUAL_AUTHORIZATION_TYPE);

            return $handler->handle($request);
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
                    $request = $request->withAttribute('error', ErrorMessages::INVALID_AUTHORIZATION_TYPE);

                    return $handler->handle($request);
                }

                $decodedData = $this->jwtService->decode($token);

                $request = $request->withAttribute('decodedData', $decodedData);

                return $handler->handle($request);
            }
        } catch (ExpiredException | BeforeValidException | SignatureInvalidException | UnexpectedValueException $e) {
            $this->log->warning($e->getMessage(), [
                'route' => $request->getUri()->getPath(),
            ]);

            $request = $request->withAttribute('error', ErrorMessages::UNAUTHORIZED);

            return $handler->handle($request);
        }
    }
}
