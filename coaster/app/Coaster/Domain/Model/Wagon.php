<?php

namespace App\Coaster\Domain\Model;

use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use DateInterval;
use Exception;
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
        if ($speed <= 0) {
            throw new InvalidArgumentException("Speed must be greater than 0 m/s.");
        }

        if ($numberOfPlaces <= 0) {
            throw new InvalidArgumentException("Number of places must be greater than 0.");
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
     * @throws Exception
     */
    public function calculateDurationForLength(float $distanceLength): DateInterval
    {
        if ($this->speed <= 0) {
            throw new LogicException("Speed must be greater than zero.");
        }

        return new DateInterval('PT' . ceil($distanceLength / $this->speed) . 'S');
    }
}
