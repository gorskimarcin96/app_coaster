<?php

namespace App\Coaster\Domain\ValueObject;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use DateInterval;
use DateTimeImmutable;
use Exception;

final readonly class RidePlan
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
    public function calculateWagonEndTime(): DateTimeImmutable
    {
        return $this->startTime->add($this->wagon->calculateDurationForLength($this->coaster->fullDistance()));
    }

    /**
     * @throws Exception
     */
    public function calculateWagonEndTimeWithBreak(): DateTimeImmutable
    {
        return $this->calculateWagonEndTime()->add(new DateInterval('PT5M'));
    }

    /**
     * @throws Exception
     */
    public function isFeasible(): bool
    {
        return $this->coaster->isOpenForDateTime($this->calculateWagonEndTime());
    }
}
