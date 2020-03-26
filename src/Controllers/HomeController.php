<?php

namespace App\Controllers;

use function App\Helpers\env;
use App\Interfaces\HttpStatusCodes;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends ApiController
{
    public function home(Request $request, Response $response): Response
    {
        return $this->response($response, ['status' => HttpStatusCodes::OK, 'message' => env('APP_NAME').' online.'], HttpStatusCodes::OK);
    }
}
