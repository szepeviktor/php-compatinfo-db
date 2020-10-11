<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

use Bartlett\CompatInfoDb\Infrastructure\ProjectRequirements;

class DiagnoseHandler implements CommandHandlerInterface
{
    public function __invoke(DiagnoseCommand $command)
    {
        return new ProjectRequirements($command);
    }
}
