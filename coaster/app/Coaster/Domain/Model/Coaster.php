<?php

namespace App\Coaster\Domain\Model;

use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;

class Coaster
{
    private function __construct(
        public readonly CoasterId $id,
        public readonly int $personNumber,
        public readonly int $clientNumber,
        public readonly int $distanceLength,
        public readonly TimeRange $timeRange,
    ) {
    }

    public static function register(
        int $personNumber,
        int $clientNumber,
        int $distanceLength,
        TimeRange $timeRange,
    ): Coaster {
        return new Coaster(
            CoasterId::generate(),
            $personNumber,
            $clientNumber,
            $distanceLength,
            $timeRange,
        );
    }

    public static function fromPersistence(
        CoasterId $id,
        int $personNumber,
        int $clientNumber,
        int $distanceLength,
        TimeRange $timeRange,
    ): Coaster {
        return new Coaster(
            $id,
            $personNumber,
            $clientNumber,
            $distanceLength,
            $timeRange,
        );
    }

    public function withUpdatedData(
        int $personNumber,
        int $clientNumber,
        TimeRange $timeRange,
    ): Coaster {
        return new Coaster(
            $this->id,
            $personNumber,
            $clientNumber,
            $this->distanceLength,
            $timeRange,
        );
    }
}
