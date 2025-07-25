<?php

declare(strict_types=1);

namespace App\Coaster\Application\Exception;

use InvalidArgumentException;

final class InvalidCommandArgumentException extends InvalidArgumentException
{
    public function __construct(string $fieldName)
    {
        parent::__construct(sprintf("Field \"%s\" is not exists.", $fieldName));
    }
}
