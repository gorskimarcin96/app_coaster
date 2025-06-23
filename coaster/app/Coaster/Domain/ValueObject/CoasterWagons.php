<?php

namespace App\Coaster\Domain\ValueObject;

use App\Coaster\Domain\Exception\CoasterHasNotWagonsException;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\CoasterStatus;
use App\Coaster\Domain\Model\Wagon;
use DateTimeImmutable;
use Exception;

final readonly class CoasterWagons
{
    public const REQUIRED_PERSONNEL_TO_COASTER = 1;
    public const REQUIRED_PERSONNEL_TO_WAGONS = 2;

    public function __construct(
        public Coaster $coaster,
        /** @var Wagon[] */
        public array $wagons,
    ) {
    }

    /**
     * todo - i need unit test!
     * @throws Exception
     */
    public function countRideNumberOfClientInCoasterInDay(): int
    {
        $freePlaces = 0;

        foreach ($this->wagons as $wagon) {
            $freePlaces += $wagon->countRidesInTimeRange(
                    $this->coaster->timeRange,
                    $wagon->calculateDurationWagonRideForDistance($this->coaster->calculateFullDistance()),
                ) * ($wagon->numberOfPlaces - self::REQUIRED_PERSONNEL_TO_WAGONS);
        }

        return $freePlaces;
    }

    /**
     * todo - i need unit test!
     * @throws Exception
     */
    public function averageRideNumberOfClientWagonInDay(): int
    {
        return count($this->wagons)
            ? floor($this->countRideNumberOfClientInCoasterInDay() / count($this->wagons))
            : throw new CoasterHasNotWagonsException($this->coaster->id);
    }

    /**
     * todo - i need unit test!
     */
    public function calculateNeedsPersonnelInCasterWithWagonsOfNumber(int $wagonsOfNumber): int
    {
        return $wagonsOfNumber * self::REQUIRED_PERSONNEL_TO_WAGONS + self::REQUIRED_PERSONNEL_TO_COASTER;
    }

    public function status(): CoasterStatus
    {
        try {
            return match (true) {
                $this->isSmallNumberOfClients() => CoasterStatus::EXCESS_WAGONS,
                $this->isMissingClients() => CoasterStatus::MISSING_CLIENTS,
                $this->isMissingWagonsAndPersonnel() => CoasterStatus::MISSING_WAGONS_AND_PERSONNEL,
                $this->isMissingWagons() => CoasterStatus::MISSING_WAGONS,
                $this->isMissingPersonnel() => CoasterStatus::MISSING_PERSONNEL,
                $this->isExcessPersonnel() => CoasterStatus::EXCESS_PERSONNEL,
                default => CoasterStatus::OK
            };
        } catch (CoasterHasNotWagonsException) {
            return CoasterStatus::MISSING_WAGONS;
        }
    }

    private function isMissingClients(): bool
    {
        return !$this->coaster->clientNumber;
}

    private function isSmallNumberOfClients(): bool
    {
        return count($this->wagons) > 1
            && $this->coaster->clientNumber <= $this->countRideNumberOfClientInCoasterInDay() / 2;
    }

    private function isExcessPersonnel(): bool
    {
        return $this->coaster->personNumber > $this->calculateNeedsPersonnel();
    }

    private function isMissingWagonsAndPersonnel(): bool
    {
        return count($this->wagons) && $this->calculateMissingWagons() && $this->calculateMissingPersonnel();
    }

    public function isMissingWagons(): bool
    {
        return count($this->wagons) && $this->calculateMissingWagons() > 0;
    }

    private function isMissingPersonnel(): bool
    {
        return $this->calculateMissingPersonnel() > 0;
    }

    public function calculateMissingWagons(): int
    {
        return ceil($this->coaster->clientNumber / $this->averageRideNumberOfClientWagonInDay()) - count($this->wagons);
    }

    public function calculateMissingPersonnel(): int
    {
        $numberOfNeedsPersonnel = $this->calculateNeedsPersonnelInCasterWithWagonsOfNumber($this->calculateNeedsWagons());

        return $numberOfNeedsPersonnel - $this->coaster->personNumber;
    }

    public function calculateExcessWagons(): int
    {
        return -1 * $this->calculateMissingWagons();
    }

    public function calculateExcessPersonnel(): int
    {
        return -1 * $this->calculateMissingPersonnel();
    }

    public function calculateNeedsWagons(): int
    {
        return count($this->wagons) + $this->calculateMissingWagons();
    }

    public function calculateNeedsPersonnel(): int
    {
        return $this->calculateNeedsPersonnelInCasterWithWagonsOfNumber($this->calculateNeedsWagons());
    }
}
