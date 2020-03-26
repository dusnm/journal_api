<?php

namespace App\Traits;

use Psr\Http\Message\ResponseInterface as Response;

trait ResponseTrait
{
    protected function response(Response $response, $data, int $statusCode): Response
    {
        $response->getBody()->write(json_encode($data, JSON_THROW_ON_ERROR));

        return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }
}
