<?php

/** @var \Slim\App $app */
use App\Controllers\RegistrationController;

$app->post('/api/register', [RegistrationController::class, 'register']);
