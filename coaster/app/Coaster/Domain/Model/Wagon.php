<?php

namespace App\Coaster\Domain\Model;

use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;

class Wagon
{
    private function __construct(
        public readonly WagonId $id,
        public readonly CoasterId $coasterId,
        public readonly int $numberOfPlaces,
        public readonly float $speed,
    ) {
    }

    public static function register(
        CoasterId $coasterId,
        int $numberOfPlaces,
        float $speed,
    ): Wagon {
        return new Wagon(
            WagonId::generate(),
            $coasterId,
            $numberOfPlaces,
            $speed,
        );
    }

    public static function fromPersistence(
        WagonId $id,
        CoasterId $coasterId,
        int $numberOfPlaces,
        float $speed,
    ): Wagon {
        return new Wagon(
            $id,
            $coasterId,
            $numberOfPlaces,
            $speed,
        );
    }
}
