<?php

namespace App\Coaster\Domain\ValueObject;

use DateTimeInterface;
use InvalidArgumentException;

final readonly class TimeRange
{
    public function __construct(
        public DateTimeInterface $fromDate,
        public DateTimeInterface $toDate,
    ) {
        if ($fromDate > $toDate) {
            throw new InvalidArgumentException('Start must be before end.');
        }
    }

    public function getStart(): DateTimeInterface
    {
        return $this->fromDate;
    }

    public function getEnd(): DateTimeInterface
    {
        return $this->toDate;
    }

    public function includes(DateTimeInterface $dateTime): bool
    {
        return $dateTime >= $this->fromDate && $dateTime <= $this->toDate;
    }
}
