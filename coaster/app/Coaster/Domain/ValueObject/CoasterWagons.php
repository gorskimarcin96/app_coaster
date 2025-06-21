<?php

namespace App\Coaster\Domain\ValueObject;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use DateTimeImmutable;
use Exception;

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

    public function countRequiredPersonalNumber(): int
    {
        return self::REQUIRED_PERSONNEL_TO_COASTER + count($this->wagons) * self::REQUIRED_PERSONNEL_TO_WAGONS;
    }

    /**
     * todo - i need unit test!
     * @throws Exception
     */
    public function countRideNumberOfClientInCoasterInDay(): int
    {
        $freePlaces = 0;

        foreach ($this->wagons as $wagon) {
            $ridePlanner = new RidePlanner($wagon, $this->coaster, new DateTimeImmutable());
            $freePlaces += $wagon->countRidesInTimeRange($this->coaster->timeRange, $ridePlanner->calculateDurationWagonRide())
                * ($wagon->numberOfPlaces - self::REQUIRED_PERSONNEL_TO_WAGONS);
        }

        return $freePlaces;
    }

    /**
     * todo - i need unit test!
     * @throws Exception
     */
    public function averageRideNumberOfClientWagonInDay(): int
    {
        return floor($this->countRideNumberOfClientInCoasterInDay() / count($this->wagons));
    }

    /**
     * todo - i need unit test!
     */
    public function calculateNeedsPersonnelInCasterWithWagonsOfNumber(int $wagonsOfNumber): int
    {
        return $wagonsOfNumber * self::REQUIRED_PERSONNEL_TO_WAGONS + self::REQUIRED_PERSONNEL_TO_COASTER;
    }
}
