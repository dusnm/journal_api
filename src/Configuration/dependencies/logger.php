<?php
/**
 * Application dependency
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
use MongoDB\Client as MongoDBClient;
use Monolog\Handler\MongoDBHandler;
use Monolog\Logger;
use function App\Helpers\env;

$mongoDBClient = new MongoDBClient(
    env('MONGODB_CONNECTION_STRING')
);

$logger = new Logger(
    env('MONOLOG_LOGGER_NAME', 'journal-api')
);

$logger->pushHandler(
    new MongoDBHandler(
        $mongoDBClient,
        env('MONGODB_DATABASE', 'logs'),
        env('MONGODB_LOGS_COLLECTION', 'logs')
    )
);

return [
    Logger::class => $logger,
];
