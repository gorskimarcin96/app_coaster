<?php

declare(strict_types=1);

namespace App\Coaster\Application\Query\GetCoasterByIdHandler;

final readonly class GetCoasterByIdQuery
{
    public function __construct(public string $id)
    {
    }
}
