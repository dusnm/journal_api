<?php
/**
 * Collection of routes related to the journal functionality
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
use App\Controllers\JournalController;
use App\Middleware\TokenDecodingMiddleware;
use Slim\Routing\RouteCollectorProxy;

/** @var \Slim\App $app */
$app->group('/api/journal', function (RouteCollectorProxy $group) {
    $group->get('/', [JournalController::class, 'read']);
    $group->get('/{id}', [JournalController::class, 'readById']);
    $group->post('/', [JournalController::class, 'create']);
    $group->put('/{id}', [JournalController::class, 'update']);
    $group->delete('/{id}', [JournalController::class, 'delete']);
})->add(TokenDecodingMiddleware::class);
