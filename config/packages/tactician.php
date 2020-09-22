<?php declare(strict_types=1);

use Bartlett\CompatInfoDb\Application\Command\DiagnoseCommand;
use Bartlett\CompatInfoDb\Application\Command\DiagnoseHandler;
use Bartlett\CompatInfoDb\Application\Command\InitCommand;
use Bartlett\CompatInfoDb\Application\Command\InitHandler;
use Bartlett\CompatInfoDb\Application\Command\ListCommand;
use Bartlett\CompatInfoDb\Application\Command\ListHandler;
use Bartlett\CompatInfoDb\Application\Command\PublishCommand;
use Bartlett\CompatInfoDb\Application\Command\PublishHandler;
use Bartlett\CompatInfoDb\Application\Command\ReleaseCommand;
use Bartlett\CompatInfoDb\Application\Command\ReleaseHandler;
use Bartlett\CompatInfoDb\Application\Command\ShowCommand;
use Bartlett\CompatInfoDb\Application\Command\ShowHandler;

use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\Locator\HandlerLocator;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * Build the Container with Tactician services
 *
 * @link https://github.com/thephpleague/tactician
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

    $services->set(DiagnoseHandler::class)
        // for ContainerLocator of https://github.com/thephpleague/tactician-container
        ->public()
    ;
    $services->set(InitHandler::class)
        // for ContainerLocator of https://github.com/thephpleague/tactician-container
        ->public()
    ;
    $services->set(ListHandler::class)
        // for ContainerLocator of https://github.com/thephpleague/tactician-container
        ->public()
    ;
    $services->set(PublishHandler::class)
        // for ContainerLocator of https://github.com/thephpleague/tactician-container
        ->public()
    ;
    $services->set(ReleaseHandler::class)
        // for ContainerLocator of https://github.com/thephpleague/tactician-container
        ->public()
    ;
    $services->set(ShowHandler::class)
        // for ContainerLocator of https://github.com/thephpleague/tactician-container
        ->public()
    ;

    // @link https://tactician.thephpleague.com/plugins/container/
    $services->set(HandlerLocator::class, ContainerLocator::class)
        ->arg('$commandNameToHandlerMap', [
            DiagnoseCommand::class => DiagnoseHandler::class,
            InitCommand::class => InitHandler::class,
            ListCommand::class => ListHandler::class,
            PublishCommand::class => PublishHandler::class,
            ReleaseCommand::class => ReleaseHandler::class,
            ShowCommand::class => ShowHandler::class,
        ])
    ;
};
