<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$connections = require __DIR__.'/connections.php';

$capsule = new Capsule();
$capsule->addConnection($connections[env('DB_DRIVER', 'mysql')]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

return $capsule;
