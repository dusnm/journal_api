<?php

use function App\Helpers\env;
use DI\Bridge\Slim\Bridge;
use Dotenv\Dotenv;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../src/Helpers/helpers.php';

$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

$container = require_once __DIR__.'/../src/configuration/dependency_container.php';

$app = Bridge::create($container);

require_once __DIR__.'/../src/routes/home.php';
require_once __DIR__.'/../src/routes/register.php';
require_once __DIR__.'/../src/routes/verify.php';

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(env('APP_DEBUG_MODE', false), false, false);

$app->run();
