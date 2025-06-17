<?php

namespace App\Coaster\Application\Command\Exception;

final class EntityNotFoundException extends \Exception
{
    public function __construct(string $entityName)
    {
        parent::__construct(sprintf('Entity "%s" not found.', $entityName));
    }
}
