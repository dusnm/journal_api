<?php
/**
 * Application dependency
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
use function App\DB\bootstrapEloquent;
use function App\Helpers\env;
use Illuminate\Database\Capsule\Manager as Capsule;

$connections = require __DIR__.'/../../DB/connections.php';

$capsule = bootstrapEloquent($connections[env('DB_DRIVER', 'mysql')]);

return [
    Capsule::class => $capsule,
];
