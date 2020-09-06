<?php
/**
 * Collection of routes related to the user functionality
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
use App\Controllers\EmailVerificationController;
use App\Controllers\LoginController;
use App\Controllers\PasswordController;
use App\Controllers\RegistrationController;
use App\Middleware\TokenDecodingMiddleware;
use Slim\Routing\RouteCollectorProxy;

/** @var \Slim\App $app */
$app->group('/api/user', function (RouteCollectorProxy $group) {
    $group->post('/register', [RegistrationController::class, 'register']);
    $group->post('/login', [LoginController::class, 'login']);
    $group->get('/verify', [EmailVerificationController::class, 'verify'])->add(TokenDecodingMiddleware::class);
    $group->get('/request-password-reset', [PasswordController::class, 'requestPasswordReset']);
    $group->post('/password-reset', [PasswordController::class, 'resetPassword'])->add(TokenDecodingMiddleware::class);
});
