<?php declare(strict_types=1);

use Bartlett\CompatInfoDb\Presentation\Console\Application;
use Bartlett\CompatInfoDb\Presentation\Console\ApplicationInterface;
use Bartlett\CompatInfoDb\Presentation\Console\Command\FactoryCommandLoader;
use Bartlett\CompatInfoDb\Presentation\Console\Input\Input;
use Bartlett\CompatInfoDb\Presentation\Console\Output\Output;

use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

/**
 * Build the Container with common parameters and services
 *
 * @param ContainerConfigurator $containerConfigurator
 * @return void
 * @since 3.0.0
 */
return static function (ContainerConfigurator $containerConfigurator): void
{
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
    ;

    $services->set(InputInterface::class, Input::class);
    $services->set(OutputInterface::class, Output::class);

    $services->set(ApplicationInterface::class, Application::class)
        // for bin file
        ->public()
    ;

    // @link https://symfony.com/doc/current/console/lazy_commands.html#factorycommandloader
    $services->set(CommandLoaderInterface::class, FactoryCommandLoader::class)
        ->arg('$commands', [tagged_iterator('console.command')])
        // for bin file
        ->public()
    ;
};