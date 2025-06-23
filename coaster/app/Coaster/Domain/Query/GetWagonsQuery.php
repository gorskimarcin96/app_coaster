<?php

declare(strict_types=1);

namespace App\Coaster\Domain\Query;

final readonly class GetWagonsQuery
{
    public function __construct(public string $coasterId)
    {
    }
}
