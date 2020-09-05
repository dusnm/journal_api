<?php
/**
 * Builds the dependency container
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Configuration;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

class ContainerAssembler
{
    /**
     * Builds a dependency container
     *
     * @throws \Exception
     *
     * @return \Psr\Container\ContainerInterface
     */
    public function assemble(): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->useAnnotations(false);

        $dependencyDirectoryPath = __DIR__.DIRECTORY_SEPARATOR.'dependencies';
        $dependencyFiles = scandir($dependencyDirectoryPath);

        foreach ($dependencyFiles as $dependencyFile) {
            if (preg_match('#\.php$#', $dependencyFile)) {
                $dependency = require $dependencyDirectoryPath.DIRECTORY_SEPARATOR.$dependencyFile;
                $builder->addDefinitions($dependency);
            }
        }

        return $builder->build();
    }
}
