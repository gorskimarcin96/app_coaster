<?php

namespace App\Coaster\Application\DTO;

final readonly class WagonDTO
{
    public function __construct(
        public string $id,
        public string $coasterId,
        public int $numberOfPlaces,
        public float $speed,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'coasterId' => $this->coasterId,
            'numberOfPlaces' => $this->numberOfPlaces,
            'speed' => $this->speed,
        ];
    }
}
