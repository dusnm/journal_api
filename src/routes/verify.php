<?php

/** @var \Slim\App $app */
use App\Controllers\EmailVerificationController;
use App\Middleware\TokenDecodingMiddleware;

$app->get('/api/verify', [EmailVerificationController::class, 'verify'])->add(TokenDecodingMiddleware::class);
