<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\DiagnoseCommand as AppDiagnoseCommand;
use Bartlett\CompatInfoDb\Application\Service\Checker;
use Bartlett\CompatInfoDb\DatabaseFactory;
use Bartlett\CompatInfoDb\Presentation\Console\Style;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Checks the minimum requirements on current platform for the phar distribution
 */
class DiagnoseCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'bartlett:diagnose';

    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Diagnoses the system to identify common errors')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $diagnoseCommand = new AppDiagnoseCommand();
        $diagnoseCommand->databaseParams = DatabaseFactory::getDsn('sqlite');

        $projectRequirements = $this->commandBus->handle($diagnoseCommand);

        $style = new Style($input, $output);

        $checker = new Checker($style);
        $checker->printDiagnostic($projectRequirements);

        return 0;
    }
}
