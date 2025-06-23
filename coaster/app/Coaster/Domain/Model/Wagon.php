<?php

declare(strict_types=1);

namespace App\Coaster\Domain\Model;

use Exception;
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
        public readonly int $seats,
        public readonly float $speedInMetersPerSecond,
    ) {
        if ($this->speedInMetersPerSecond <= 0) {
            throw new InvalidArgumentException("speedInMetersPerSecond must be greater than 0 m/s.");
        }

        if ($this->seats < 0) {
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
        int $seats,
        float $speedInMetersPerSecond,
    ): Wagon {
        return new Wagon(
            WagonId::generate(),
            $coasterId,
            $seats,
            $speedInMetersPerSecond,
        );
    }

    public static function fromPersistence(
        WagonId $id,
        CoasterId $coasterId,
        int $seats,
        float $speedInMetersPerSecond,
    ): Wagon {
        return new Wagon(
            $id,
            $coasterId,
            $seats,
            $speedInMetersPerSecond,
        );
    }

    /**
     * @throws Exception
     */
    public function calculateDurationWagonRideForDistance(int $distance): DateInterval
    {
        return $this->speedInMetersPerSecond > 0
            ? new DateInterval('PT' . ceil($distance / $this->speedInMetersPerSecond) . 'S')
            : throw new LogicException("speedInMetersPerSecond must be greater than zero.");
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
        $current = $timeRange->from;

        while ($current <= $timeRange->to) {
            $current = $current->add($rideDuration)->add($this->getBreakDuration());
            $count++;
        }

        return $count;
    }
}
