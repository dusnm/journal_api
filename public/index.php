<?php

use App\Configuration\ContainerAssembler;
use App\Handlers\CustomErrorHandler;
use App\Middleware\CorsMiddleware;
use DI\Bridge\Slim\Bridge;
use Dotenv\Dotenv;
use Monolog\Logger;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../src/Helpers/helpers.php';
require      __DIR__.'/../src/DB/bootstrapEloquent.php';

(Dotenv::createImmutable(__DIR__.'/../'))->load();

$container = (new ContainerAssembler())->assemble();

$app = Bridge::create($container);

require_once __DIR__.'/../src/routes/home.php';
require_once __DIR__.'/../src/routes/user.php';
require_once __DIR__.'/../src/routes/journal.php';
require_once __DIR__.'/../src/routes/image.php';

$customErrorHandler = new CustomErrorHandler($app->getCallableResolver(), $app->getResponseFactory(), $container->get(Logger::class));
$customErrorHandler->forceContentType('application/json');

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->add(CorsMiddleware::class);

$errorMiddleware = $app->addErrorMiddleware(false, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->run();
