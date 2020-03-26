<?php

/** @var \Slim\App $app */
use App\Controllers\JournalController;
use App\Middleware\TokenDecodingMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api/journal', function (RouteCollectorProxy $group) {
    $group->get('/', [JournalController::class, 'read']);
    $group->post('/', [JournalController::class, 'create']);
    $group->delete('/{id}', [JournalController::class, 'delete']);
})->add(TokenDecodingMiddleware::class);
