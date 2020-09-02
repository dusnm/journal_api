<?php

namespace App\Tests;

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

(Dotenv::createImmutable(__DIR__.'/../'))->load();

class TestCase extends PHPUnitTestCase
{
    /**
     * @var Illuminate\Database\Capsule\Manager
     */
    private Manager $capsule;

    protected function setUp(): void
    {
        $this->capsule = require __DIR__.'/../src/db/capsule.php';
    }
}
