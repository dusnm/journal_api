<?php
/**
 * Application dependency
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
use App\Interfaces\ImageUploadInterface;
use function App\Helpers\env;
use function DI\get;

$filesystems = require __DIR__.'/../filesystems.php';

return [
    // Interface to implementation binding
    ImageUploadInterface::class => get(
        $filesystems[env('FILE_SYSTEM_DRIVER', 'local')]['driver']
    ),
];
