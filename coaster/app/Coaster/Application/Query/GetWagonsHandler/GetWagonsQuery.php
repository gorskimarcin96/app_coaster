<?php

declare(strict_types=1);

namespace App\Coaster\Application\Query\GetWagonsHandler;

final readonly class GetWagonsQuery
{
    public function __construct(public string $coasterId)
    {
    }
}
