<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure\Bus\Command;

use Bartlett\CompatInfoDb\Application\Command\CommandBusInterface;
use Bartlett\CompatInfoDb\Application\Command\CommandInterface;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
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

    public function __construct(HandlerLocator $locator)
    {
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
