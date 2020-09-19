<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader as SymfonyFactoryCommandLoader;

class FactoryCommandLoader extends SymfonyFactoryCommandLoader implements CommandLoaderInterface
{
    public function __construct(iterable $commands)
    {
        $factories = [];

        foreach ($commands as $references) {
            foreach ($references as $command) {
                $factories[$command->getName()] = function() use ($command) { return $command; };
            }
        }

        parent::__construct($factories);
    }
}
