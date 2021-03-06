<?php
/**
 * Bootstraping class for testing
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Tests;

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Input\ArrayInput;
use function APP\DB\bootstrapEloquent;

(Dotenv::createImmutable(__DIR__.'/../'))->load();

require __DIR__.'/../src/DB/bootstrapEloquent.php';

abstract class TestCase extends PHPUnitTestCase
{
    /**
     * @var PhinxApplication
     */
    protected PhinxApplication $phinx;

    /**
     * @var array $connections
     */
    protected array $connections;

    /**
     * @var Illuminate\Database\Capsule\Manager
     */
    protected Manager $capsule;

    protected function setUp(): void
    {
        $this->phinx = new PhinxApplication();
        $this->phinx->setAutoExit(false);

        $this->phinx->run(new ArrayInput([
            'command' => 'migrate',
            '--environment' => 'testing',
            '--quiet' => null,
        ]));

        $this->phinx->run(new ArrayInput([
            'command' => 'seed:run',
            '--seed' => ['UserTestSeeder'],
            '--environment' => 'testing',
            '--quiet' => null,
        ]));

        $this->connections = require __DIR__.'/../src/DB/connections.php';
        $this->capsule = bootstrapEloquent($this->connections['sqlite']);
    }

    protected function tearDown(): void
    {
        $this->capsule->table('images')->truncate();
        $this->capsule->table('journals')->truncate();
        $this->capsule->table('users')->truncate();

        $this->phinx->run(new ArrayInput([
            'command' => 'rollback',
            '--environment' => 'testing',
            '--target' => 0,
            '--quiet' => null,
        ]));
    }
}
