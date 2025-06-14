<?php

namespace App\Coaster\Application\Query\GetWagonsHandler;

final readonly class GetWagonsQuery
{
    public function __construct(public string $coasterId)
    {
    }
}
