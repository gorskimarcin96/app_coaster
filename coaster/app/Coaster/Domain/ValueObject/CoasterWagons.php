<?php

namespace App\Coaster\Domain\ValueObject;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;

final readonly class CoasterWagons
{
    private const REQUIRED_PERSONNEL_TO_COASTER = 1;
    private const REQUIRED_PERSONNEL_TO_WAGONS = 2;

    public function __construct(
        public Coaster $coaster,
        /** @var Wagon[] */
        public array $wagons,
    ) {
    }

    public function requiredPersonalNumber(): int
    {
        return self::REQUIRED_PERSONNEL_TO_COASTER + count($this->wagons) * self::REQUIRED_PERSONNEL_TO_WAGONS;
    }
}
