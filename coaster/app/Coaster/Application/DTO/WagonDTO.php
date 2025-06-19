<?php

namespace App\Coaster\Application\DTO;

use App\Coaster\Domain\Model\Wagon;
use DateTimeImmutable;
use DateTimeInterface;

final readonly class WagonDTO
{
    private function __construct(
        public string $id,
        public string $coasterId,
        public int $numberOfPlaces,
        public float $speed,
        public ?DateTimeImmutable $startedAt,
        public ?DateTimeImmutable $expectedReturnAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'coasterId' => $this->coasterId,
            'numberOfPlaces' => $this->numberOfPlaces,
            'speed' => $this->speed,
            'startedAt' => $this->startedAt?->format(DateTimeInterface::ATOM),
            'expectedReturnAt' => $this->expectedReturnAt?->format(DateTimeInterface::ATOM),
        ];
    }

    public static function fromEntity(Wagon $entity): self
    {
        return new self(
            $entity->id,
            $entity->coasterId,
            $entity->numberOfPlaces,
            $entity->speed,
            $entity->startedAt,
            $entity->expectedReturnAt,
        );
    }
}
