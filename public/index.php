<?php

use App\Handlers\CustomErrorHandler;
use App\Middleware\CorsMiddleware;
use DI\Bridge\Slim\Bridge;
use Dotenv\Dotenv;
use Monolog\Logger;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../src/Helpers/helpers.php';

$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

$container = require_once __DIR__.'/../src/configuration/dependency_container.php';

$app = Bridge::create($container);

require_once __DIR__.'/../src/routes/home.php';
require_once __DIR__.'/../src/routes/user.php';
require_once __DIR__.'/../src/routes/journal.php';

$customErrorHandler = new CustomErrorHandler($app->getCallableResolver(), $app->getResponseFactory(), $container->get(Logger::class));
$customErrorHandler->forceContentType('application/json');

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->add(CorsMiddleware::class);

$errorMiddleware = $app->addErrorMiddleware(false, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->run();
