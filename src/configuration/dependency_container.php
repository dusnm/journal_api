<?php

use function App\Helpers\env;
use DI\ContainerBuilder;
use function DI\create;
use Illuminate\Database\Capsule\Manager as Capsule;

$containerBuilder = new ContainerBuilder();

$connections = require_once __DIR__.'/../db/connections.php';

$capsule = new Capsule();
$capsule->addConnection($connections[env('DB_DRIVER', 'mysql')]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$smtpTransport = new Swift_SmtpTransport(env('MAILER_HOST'), env('MAILER_PORT'), env('MAILER_ENCRYPTION'));
$smtpTransport->setUsername(env('MAILER_USERNAME'));
$smtpTransport->setPassword(env('MAILER_PASSWORD'));

$containerBuilder->addDefinitions([
    Capsule::class => $capsule,
    Swift_SmtpTransport::class => $smtpTransport,
    Swift_Mailer::class => create(Swift_Mailer::class)->constructor($smtpTransport),
]);

return $containerBuilder->build();
