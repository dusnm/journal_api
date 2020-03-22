<?php

/** @var \Slim\App $app */
use App\Controllers\HomeController;

$app->get('/', [HomeController::class, 'home']);
