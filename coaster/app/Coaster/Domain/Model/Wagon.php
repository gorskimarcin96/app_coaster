<?php

namespace App\Coaster\Domain\Model;

use App\Coaster\Domain\Exception\WagonAlreadyRunException;
use App\Coaster\Domain\Exception\WagonHasBreakException;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

class Wagon
{
    private function __construct(
        public readonly WagonId $id,
        public readonly CoasterId $coasterId,
        public readonly int $numberOfPlaces,
        public readonly float $speed,
        public readonly ?DateTimeImmutable $startedAt = null,
        public readonly ?DateTimeImmutable $expectedReturnAt = null,
    ) {
        if ($this->speed <= 0) {
            throw new InvalidArgumentException("Speed must be greater than 0 m/s.");
        }

        if ($this->numberOfPlaces < 0) {
            throw new InvalidArgumentException("The number of places must be greater than or equal to zero.");
        }

        if ($this->startedAt > $this->expectedReturnAt) {
            throw new InvalidArgumentException("Started at must be greater than excepted return at.");
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
        ?DateTimeImmutable $startedAt = null,
        ?DateTimeImmutable $expectedReturnAt = null,
    ): Wagon {
        return new Wagon(
            $id,
            $coasterId,
            $numberOfPlaces,
            $speed,
            $startedAt,
            $expectedReturnAt,
        );
    }

    public function run(DateTimeImmutable $startTime, DateInterval $rideDuration): Wagon
    {
        if ($this->expectedReturnAt !== null && $this->expectedReturnAt >= $startTime) {
            throw new WagonAlreadyRunException($this->id);
        }

        if ($this->expectedReturnAt !== null && $this->expectedReturnAt->add($this->getBreakDuration()) >= $startTime) {
            throw new WagonHasBreakException($this->id);
        }

        return new Wagon(
            $this->id,
            $this->coasterId,
            $this->numberOfPlaces,
            $this->speed,
            $startTime,
            $startTime->add($rideDuration),
        );
    }

    public function getBreakDuration(): DateInterval
    {
        return new DateInterval('PT5M');
    }

    public function isRunningAt(DateTimeInterface $dateTime): bool
    {
        return $this->startedAt !== null
            && $this->expectedReturnAt !== null
            && $dateTime >= $this->startedAt
            && $dateTime <= $this->expectedReturnAt;
    }
}
