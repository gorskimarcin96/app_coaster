<?php

namespace App\Coaster\Domain\Exception;

use App\Coaster\Domain\ValueObject\CoasterId;
use RuntimeException;

final class CoasterHasNotWagonsException extends RuntimeException
{
    public function __construct(CoasterId $id)
    {
        parent::__construct(sprintf('The coaster %s has not wagons', $id));
    }
}
