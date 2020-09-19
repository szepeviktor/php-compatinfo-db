<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Bus\Command;

use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Command\CommandInterface;
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
use Bartlett\CompatInfoDb\Application\Service\JsonFileHandler;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;

/**
 * Tactician Command Bus
 *
 * @link https://github.com/thephpleague/tactician
 * @link https://tactician.thephpleague.com/tweaking-tactician/
 * @since 3.0.0
 */
class TacticianCommandBus implements CommandBusInterface
{
    private $messageBus;

    public function __construct(JsonFileHandler $jsonFileHandler)
    {
        $locator = new InMemoryLocator();
        $locator->addHandler(new DiagnoseHandler(), DiagnoseCommand::class);
        $locator->addHandler(new InitHandler($jsonFileHandler), InitCommand::class);
        $locator->addHandler(new ListHandler(), ListCommand::class);
        $locator->addHandler(new PublishHandler($jsonFileHandler), PublishCommand::class);
        $locator->addHandler(new ReleaseHandler($jsonFileHandler), ReleaseCommand::class);
        $locator->addHandler(new ShowHandler(), ShowCommand::class);

        $handlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            $locator,
            new InvokeInflector()
        );

        $this->messageBus = new CommandBus([$handlerMiddleware]);
    }

    public function handle(CommandInterface $command)
    {
        return $this->messageBus->handle($command);
    }
}
