<?php
/**
 * Collection of routes related to the image functionality
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
use App\Controllers\ImageUploadController;
use App\Middleware\TokenDecodingMiddleware;
use Slim\Routing\RouteCollectorProxy;

/** @var Slim\App $app */
$app->group('/api/image', function (RouteCollectorProxy $group) {
    $group->post('/upload', [ImageUploadController::class, 'upload'])->add(TokenDecodingMiddleware::class);
});
