<?php

namespace App\Coaster\Application\DTO;

final readonly class CoasterDTO
{
    public function __construct(
        public string $id,
        public int $personNumber,
        public int $clientNumber,
        public int $distanceLength,
        public string $fromDate,
        public string $toDate,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'personNumber' => $this->personNumber,
            'clientNumber' => $this->clientNumber,
            'distanceLength' => $this->distanceLength,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
        ];
    }
}
