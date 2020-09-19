<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

class PublishCommand implements CommandInterface
{
    public $relVersion;
    public $relDate;
    public $relState;
}
