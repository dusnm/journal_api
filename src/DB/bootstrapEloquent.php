<?php
namespace App\DB;

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Set up eloquent database connection
 *
 * @param array $connection
 *
 * @return Capsule
 */
function bootstrapEloquent(array $connection): Capsule
{
    $capsule = new Capsule();
    $capsule->addConnection($connection);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
}
