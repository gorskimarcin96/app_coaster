<?php

namespace App\Coaster\Domain\Exception;

use App\Coaster\Domain\ValueObject\WagonId;

final class WagonCanNotRunException extends \RuntimeException
{
    public function __construct(WagonId $wagonId)
    {
        parent::__construct(sprintf('Wagon %s can not run.', $wagonId));
    }
}
