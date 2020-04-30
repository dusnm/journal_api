<?php
/** @var Slim\App $app */

use App\Controllers\ImageUploadController;
use App\Middleware\TokenDecodingMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api/image', function (RouteCollectorProxy $group) {
    $group->post('/upload', [ImageUploadController::class, 'upload'])->add(TokenDecodingMiddleware::class);
});
