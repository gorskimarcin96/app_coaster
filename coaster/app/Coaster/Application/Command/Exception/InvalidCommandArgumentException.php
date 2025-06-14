<?php

namespace App\Coaster\Application\Command\Exception;

use InvalidArgumentException;

final class InvalidCommandArgumentException extends InvalidArgumentException
{
    public function __construct(string $fieldName)
    {
        parent::__construct(sprintf("Field \"%s\" is not exists.", $fieldName));
    }
}
