<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Terminal;

use function array_map;
use function is_array;
use function sprintf;
use function wordwrap;

/**
 * @since 3.0.0
 */
final class Style extends SymfonyStyle
{
    private $lineLength;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        parent::__construct($input, $output);

        $width = (new Terminal())->getWidth() ?: self::MAX_LINE_LENGTH;
        $this->lineLength = min($width - (int) (\DIRECTORY_SEPARATOR === '\\'), self::MAX_LINE_LENGTH);
    }

    /**
     * {@inheritDoc}
     */
    public function text($message)
    {
        if (false === $style = @func_get_arg(1)) {
            parent::text($message);
            return;
        }

        $messages = is_array($message) ? array_values($message) : [$message];
        foreach ($messages as $message) {
            $message = wordwrap($message, $this->lineLength);
            $message = sprintf(
                '<%s>%s</>',
                $style,
                $message
            );
            $this->writeln($message);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function listing(array $elements)
    {
        if (false === $attributes = @func_get_arg(1)) {
            $attributes = ['type' => '*', 'style' => ''];
        }
        $type = $attributes['type'] ?? '*';
        $style = $attributes['style'] ?? '';

        $elements = array_map(function ($element) use($type, $style) {
            if (empty($style)) {
                return sprintf('   %s %s', $type, $element);
            }
            return sprintf('   <%s>%s %s</>', $style, $type, $element);
        }, $elements);

        $this->writeln($elements);
        $this->newLine();
    }
}
