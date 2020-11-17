<?php declare(strict_types=1);

/**
 * Console command to add a new PHP release in database.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Presentation\Console\Command;

use Bartlett\CompatInfoDb\Application\Command\Release\ReleaseCommand as AppReleaseCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function trim;

/**
 * Update JSON files when a new PHP version is released.
 */
class ReleaseCommand extends AbstractCommand implements CommandInterface
{
    public const NAME = 'bartlett:db:release';

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Add new PHP release')
            ->addArgument(
                'rel_version',
                InputArgument::REQUIRED,
                'New PHP release version'
            )
            ->addArgument(
                'rel_date',
                InputArgument::OPTIONAL,
                'New PHP release date',
                date('Y-m-d')
            )
            ->addArgument(
                'rel_state',
                InputArgument::OPTIONAL,
                'New PHP release state (alpha, beta, RC, stable)',
                'stable'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $releaseCommand = new AppReleaseCommand();
        $releaseCommand->relVersion = trim($input->getArgument('rel_version'));
        $releaseCommand->relDate = trim($input->getArgument('rel_date'));
        $releaseCommand->relState = trim($input->getArgument('rel_state'));

        $this->commandBus->handle($releaseCommand);

        return 0;
    }
}
