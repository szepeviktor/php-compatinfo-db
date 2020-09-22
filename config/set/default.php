<?php declare(strict_types=1);

use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Service\JsonFileHandler;
use Bartlett\CompatInfoDb\Infrastructure\Bus\Command\TacticianCommandBus;
use Bartlett\CompatInfoDb\Presentation\Console\Command\CommandInterface;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * Build the Container with default parameters and services
 *
 * @param ContainerConfigurator $containerConfigurator
 * @return void
 * @since 3.0.0
 */
return static function (ContainerConfigurator $containerConfigurator): void
{
    $containerConfigurator->import(__DIR__ . '/common.php');

    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
    ;

    // @link https://symfony.com/doc/current/service_container/tags.html#autoconfiguring-tags
    $services->instanceof(CommandInterface::class)
        ->tag('console.command')
    ;

    $services->load('Bartlett\CompatInfoDb\\', __DIR__ . '/../../src');

    $services->set(JsonFileHandler::class)
        ->arg('$refDir', __DIR__ . '/../../data/references')
    ;

    // @link https://tactician.thephpleague.com/
    $services->set(CommandBusInterface::class, TacticianCommandBus::class);

    $containerConfigurator->import(__DIR__ . '/../packages/tactician.php');
};
