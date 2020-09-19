<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console;

use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Console Application contract.
 *
 * @since 3.0.0
 */
interface ApplicationInterface extends ContainerAwareInterface
{
    public const NAME = 'Database handler for CompatInfo';
    public const VERSION = '3.x-dev';

    public function setCommandLoader(CommandLoaderInterface $commandLoader);
}
