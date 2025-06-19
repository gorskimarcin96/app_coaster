<?php

namespace App\Coaster\Domain\Exception;

use App\Coaster\Domain\ValueObject\WagonId;
use RuntimeException;

final class WagonHasBreakException extends RuntimeException
{
    public function __construct(WagonId $id)
    {
        parent::__construct(sprintf('The wagon %s has a break', $id));
    }
}
