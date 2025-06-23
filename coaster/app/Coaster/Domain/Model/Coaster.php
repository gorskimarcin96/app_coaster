<?php

namespace App\Coaster\Domain\Model;

use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use InvalidArgumentException;

class Coaster
{
    private function __construct(
        public readonly CoasterId $id,
        public readonly int $availablePersonnel,
        public readonly int $clientsPerDay,
        public readonly int $trackLengthInMeters,
        public readonly TimeRange $timeRange,
    ) {
        if ($this->availablePersonnel < 0) {
            throw new InvalidArgumentException("The number of available personnel must be greater than or equal to zero");
        }

        if ($this->clientsPerDay < 0) {
            throw new InvalidArgumentException("The number of client per day must be greater than or equal to zero");
        }

        if ($this->trackLengthInMeters <= 0) {
            throw new InvalidArgumentException("Track length must be greater than 0.");
        }
    }

    public static function register(
        int $availablePersonnel,
        int $clientsPerDay,
        int $trackLengthInMeters,
        TimeRange $timeRange,
    ): Coaster {
        return new Coaster(
            CoasterId::generate(),
            $availablePersonnel,
            $clientsPerDay,
            $trackLengthInMeters,
            $timeRange,
        );
    }

    public static function fromPersistence(
        CoasterId $id,
        int $availablePersonnel,
        int $clientsPerDay,
        int $trackLengthInMeters,
        TimeRange $timeRange,
    ): Coaster {
        return new Coaster(
            $id,
            $availablePersonnel,
            $clientsPerDay,
            $trackLengthInMeters,
            $timeRange,
        );
    }

    public function withUpdatedData(
        int $availablePersonnel,
        int $clientsPerDay,
        TimeRange $timeRange,
    ): Coaster {
        return new Coaster(
            $this->id,
            $availablePersonnel,
            $clientsPerDay,
            $this->trackLengthInMeters,
            $timeRange,
        );
    }
}
