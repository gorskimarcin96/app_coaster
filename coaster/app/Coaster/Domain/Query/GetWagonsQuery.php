<?php

namespace App\Coaster\Domain\Query;

final readonly class GetWagonsQuery
{
    public function __construct(public string $coasterId)
    {
    }
}
