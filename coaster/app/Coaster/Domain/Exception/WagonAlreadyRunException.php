<?php

namespace App\Coaster\Domain\Exception;

use App\Coaster\Domain\ValueObject\WagonId;
use RuntimeException;

final class WagonAlreadyRunException extends RuntimeException
{
    public function __construct(WagonId $id)
    {
        parent::__construct(sprintf('Wagon %s already run.', $id));
    }
}
