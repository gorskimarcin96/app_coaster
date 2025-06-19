<?php

namespace App\Coaster\Domain\Model;

use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeInterface;
use InvalidArgumentException;

class Coaster
{
    private function __construct(
        public readonly CoasterId $id,
        public readonly int $personNumber,
        public readonly int $clientNumber,
        public readonly int $distanceLength,
        public readonly TimeRange $timeRange,
    ) {
        if ($personNumber <= 0) {
            throw new InvalidArgumentException("Person number must be greater than 0.");
        }

        if ($clientNumber <= 0) {
            throw new InvalidArgumentException("Client number must be greater than 0./s.");
        }

        if ($distanceLength <= 0) {
            throw new InvalidArgumentException("Distance length must be greater than 0.");
        }
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

    public function fullDistance(): float
    {
        return $this->distanceLength * 2;
    }

    public function isOpenForDateTime(DateTimeInterface $dateTime): bool
    {
        return $this->timeRange->includes($dateTime);
    }
}
