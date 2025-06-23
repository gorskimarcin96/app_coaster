<?php

namespace App\Coaster\Domain\Model;

use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\CoasterWagons;
use App\Coaster\Domain\ValueObject\TimeRange;
use App\Coaster\Domain\ValueObject\WagonId;
use DateInterval;
use InvalidArgumentException;
use LogicException;

class Wagon
{
    private function __construct(
        public readonly WagonId $id,
        public readonly CoasterId $coasterId,
        public readonly int $numberOfPlaces,
        public readonly float $speed,
    ) {
        if ($this->speed <= 0) {
            throw new InvalidArgumentException("Speed must be greater than 0 m/s.");
        }

        if ($this->numberOfPlaces < 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'The number of places must be greater than or equal to %s',
                    CoasterWagons::REQUIRED_PERSONNEL_TO_WAGONS,
                ),
            );
        }
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

    /**
     * @throws \Exception
     */
    public function calculateDurationWagonRideForDistance(int $distance): DateInterval
    {
        return $this->speed > 0
            ? new DateInterval('PT' . ceil($distance / $this->speed) . 'S')
            : throw new LogicException("Speed must be greater than zero.");
    }

    public function getBreakDuration(): DateInterval
    {
        return new DateInterval('PT5M');
    }

    /**
     * todo - i need unit test!
     */
    public function countRidesInTimeRange(TimeRange $timeRange, DateInterval $rideDuration): int
    {
        $count = 0;
        $current = $timeRange->fromDate;

        while ($current <= $timeRange->toDate) {
            $current = $current->add($rideDuration)->add($this->getBreakDuration());
            $count++;
        }

        return $count;
    }
}
