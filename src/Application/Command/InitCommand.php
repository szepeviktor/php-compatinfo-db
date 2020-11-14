<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

class InitCommand implements CommandInterface
{
    public $refDir;
    public $appVersion;
    public $dbFilename;
    public $output;
}
