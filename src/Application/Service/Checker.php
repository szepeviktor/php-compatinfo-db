<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Service;

use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Requirements\RequirementCollection;

/**
 * @since 3.0.0
 */
final class Checker
{
    private $style;
    private $appName = 'Application';

    public function __construct(StyleInterface $style)
    {
        $this->style = $style;
    }

    public function setAppName(string $name)
    {
        $this->appName = $name;
    }

    public function printDiagnostic(RequirementCollection $requirements)
    {
        $this->style->title($this->appName . ' Requirements Checker');

        $this->style->text(sprintf('> Using PHP <info>%s</info>', PHP_VERSION));
        $this->style->text('> PHP is using the following php.ini file:');
        if ($iniPath = $requirements->getPhpIniPath()) {
            $this->style->text(sprintf('<info>%s</info>', $iniPath));
        } else {
            $this->style->text('   WARNING: No configuration file (php.ini) used by PHP!', 'fg=yellow');
        }

        $this->style->text(PHP_EOL . '> Checking ' . $this->appName . ' requirements:');

        $messages = ['ko' => [], 'error' => []];

        foreach ($requirements->getRequirements() as $requirement) {
            if ($requirement->isFulfilled()) {
                $messages['ok'][] = $requirement->getTestMessage();
                continue;
            }
            $messages['ko'][] = $requirement->getTestMessage();
            $messages['error'][] = $requirement->getHelpText();
        }
        $this->style->listing($messages['ok'], ['type' => '[x]', 'style' => 'fg=green']);
        $this->style->listing($messages['ko'], ['type' => '[ ]', 'style' => 'fg=red']);

        if (empty($messages['error'])) {
            $this->style->success('Your system is ready to run the application.');
        } else {
            $this->style->error('Your system is not ready to run the application.');
            $this->style->section('Fix the following mandatory requirements:');
            $this->style->listing($messages['error'], ['style' => 'options=bold;fg=red']);
        }
    }
}
