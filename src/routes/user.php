<?php

/** @var \Slim\App $app */
use App\Controllers\EmailVerificationController;
use App\Controllers\ImageUploadController;
use App\Controllers\LoginController;
use App\Controllers\PasswordController;
use App\Controllers\RegistrationController;
use App\Middleware\TokenDecodingMiddleware;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api/user', function (RouteCollectorProxy $group) {
    $group->post('/register', [RegistrationController::class, 'register']);
    $group->post('/login', [LoginController::class, 'login']);
    $group->get('/verify', [EmailVerificationController::class, 'verify'])->add(TokenDecodingMiddleware::class);
    $group->get('/request-password-reset', [PasswordController::class, 'requestPasswordReset']);
    $group->post('/password-reset', [PasswordController::class, 'resetPassword'])->add(TokenDecodingMiddleware::class);
    $group->post('/avatar', [ImageUploadController::class, 'uploadUserAvatar'])->add(TokenDecodingMiddleware::class);
});
