<?php
/**
 * Collection of routes related to the home functionality
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
use App\Controllers\HomeController;

/** @var \Slim\App $app */
$app->get('/', [HomeController::class, 'home']);
