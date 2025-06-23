<?php

namespace App\Coaster\Domain\ValueObject;

use App\Coaster\Domain\Exception\CoasterHasNotWagonsException;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\CoasterStatus;
use App\Coaster\Domain\Model\Wagon;
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
     * @throws Exception
     */
    public function countRideNumberOfClientInCoasterInDay(): int
    {
        $freePlaces = 0;

        foreach ($this->wagons as $wagon) {
            $freePlaces += $wagon->countRidesInTimeRange(
                    $this->coaster->timeRange,
                    $wagon->calculateDurationWagonRideForDistance($this->coaster->trackLengthInMeters),
                ) * ($wagon->seats - self::REQUIRED_PERSONNEL_TO_WAGONS);
        }

        return $freePlaces;
    }

    public function calculateNeedsPersonnelInCasterWithWagonsOfNumber(int $wagonsOfNumber): int
    {
        return $wagonsOfNumber * self::REQUIRED_PERSONNEL_TO_WAGONS + self::REQUIRED_PERSONNEL_TO_COASTER;
    }

    /**
     * @throws Exception
     */
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

    /**
     * @throws Exception
     */
    public function calculateMissingWagons(): int
    {
        return ceil($this->coaster->clientsPerDay / $this->averageRideNumberOfClientWagonInDay()) - count($this->wagons);
    }

    /**
     * @throws Exception
     */
    public function calculateMissingPersonnel(): int
    {
        $numberOfNeedsPersonnel = $this->calculateNeedsPersonnelInCasterWithWagonsOfNumber(
            $this->calculateNeedsWagons(),
        );

        return $numberOfNeedsPersonnel - $this->coaster->availablePersonnel;
    }

    /**
     * @throws Exception
     */
    public function calculateExcessWagons(): int
    {
        return -1 * $this->calculateMissingWagons();
    }

    /**
     * @throws Exception
     */
    public function calculateExcessPersonnel(): int
    {
        return -1 * $this->calculateMissingPersonnel();
    }

    /**
     * @throws Exception
     */
    public function calculateNeedsWagons(): int
    {
        return count($this->wagons) + $this->calculateMissingWagons();
    }

    /**
     * @throws Exception
     */
    public function calculateNeedsPersonnel(): int
    {
        return $this->calculateNeedsPersonnelInCasterWithWagonsOfNumber($this->calculateNeedsWagons());
    }

    private function isMissingClients(): bool
    {
        return !$this->coaster->clientsPerDay;
    }

    /**
     * @throws Exception
     */
    private function isSmallNumberOfClients(): bool
    {
        return count($this->wagons) > 1
            && $this->coaster->clientsPerDay <= $this->countRideNumberOfClientInCoasterInDay() / 2;
    }

    /**
     * @throws Exception
     */
    private function isExcessPersonnel(): bool
    {
        return $this->coaster->availablePersonnel > $this->calculateNeedsPersonnel();
    }

    /**
     * @throws Exception
     */
    private function isMissingWagonsAndPersonnel(): bool
    {
        return count($this->wagons) && $this->calculateMissingWagons() && $this->calculateMissingPersonnel();
    }

    /**
     * @throws Exception
     */
    private function isMissingWagons(): bool
    {
        return count($this->wagons) && $this->calculateMissingWagons() > 0;
    }

    /**
     * @throws Exception
     */
    private function isMissingPersonnel(): bool
    {
        return $this->calculateMissingPersonnel() > 0;
    }

    /**
     * @throws Exception
     */
    private function averageRideNumberOfClientWagonInDay(): int
    {
        return count($this->wagons)
            ? floor($this->countRideNumberOfClientInCoasterInDay() / count($this->wagons))
            : throw new CoasterHasNotWagonsException($this->coaster->id);
    }
}
