<?php

/** @var \Slim\App $app */
use App\Controllers\LoginController;

$app->post('/api/login', [LoginController::class, 'login']);
