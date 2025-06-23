<?php

declare(strict_types=1);

namespace App\Coaster\Domain\ValueObject;

use DateTimeInterface;
use InvalidArgumentException;

final readonly class TimeRange
{
    public function __construct(
        public DateTimeInterface $from,
        public DateTimeInterface $to,
    ) {
        if ($from > $to) {
            throw new InvalidArgumentException('Start must be before end.');
        }
    }
}
