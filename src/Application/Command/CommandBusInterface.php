<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

/**
 * CommandBus contract.
 *
 * @since 3.0.0
 */
interface CommandBusInterface
{
    public function handle(CommandInterface $command);
}
