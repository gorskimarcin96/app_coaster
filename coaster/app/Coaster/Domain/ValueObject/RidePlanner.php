<?php

namespace App\Coaster\Domain\ValueObject;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use DateInterval;
use DateTimeImmutable;
use Exception;
use LogicException;

class RidePlanner
{
    public function __construct(
        public Wagon $wagon,
        public Coaster $coaster,
        public DateTimeImmutable $startTime,
    ) {
    }

    /**
     * @throws Exception
     */
    private function calculateDurationWagonRide(): DateInterval
    {
        return $this->wagon->speed > 0
            ? new DateInterval('PT' . ceil($this->coaster->calculateFullDistance() / $this->wagon->speed) . 'S')
            : throw new LogicException("Speed must be greater than zero.");
    }

    /**
     * @throws Exception
     */
    public function calculateWagonEndTime(): DateTimeImmutable
    {
        return $this->startTime->add($this->calculateDurationWagonRide());
    }

    /**
     * @throws Exception
     */
    public function calculateWagonEndTimeWithBreak(): DateTimeImmutable
    {
        return $this->calculateWagonEndTime()->add($this->wagon->getBreakDuration());
    }

    /**
     * @throws Exception
     */
    public function isFeasible(): bool
    {
        return $this->coaster->isOpenForDateTime($this->calculateWagonEndTime());
    }
}
