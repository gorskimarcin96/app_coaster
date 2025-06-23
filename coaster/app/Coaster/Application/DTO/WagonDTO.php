<?php

namespace App\Coaster\Application\DTO;

use App\Coaster\Domain\Model\Wagon;

final readonly class WagonDTO
{
    private function __construct(
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

    public static function fromEntity(Wagon $entity): self
    {
        return new self(
            $entity->id,
            $entity->coasterId,
            $entity->numberOfPlaces,
            $entity->speed,
        );
    }
}
