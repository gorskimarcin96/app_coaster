<?php

declare(strict_types=1);

namespace App\Coaster\Application\Query\GetWagonByIdHandler;

final readonly class GetWagonByIdQuery
{
    public function __construct(public string $coasterId, public string $wagonId)
    {
    }
}
