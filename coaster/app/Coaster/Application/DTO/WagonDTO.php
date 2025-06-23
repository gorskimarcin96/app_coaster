<?php

declare(strict_types=1);

namespace App\Coaster\Application\DTO;

use App\Coaster\Domain\Model\Wagon;

final readonly class WagonDTO
{
    private function __construct(
        public string $id,
        public string $coasterId,
        public int $seats,
        public float $speedInMetersPerSecond,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'coasterId' => $this->coasterId,
            'seats' => $this->seats,
            'speedInMetersPerSecond' => $this->speedInMetersPerSecond,
        ];
    }

    public static function fromEntity(Wagon $entity): self
    {
        return new self(
            $entity->id->getId()->toString(),
            $entity->coasterId->getId()->toString(),
            $entity->seats,
            $entity->speedInMetersPerSecond,
        );
    }
}
