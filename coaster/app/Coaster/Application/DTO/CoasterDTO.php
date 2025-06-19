<?php

namespace App\Coaster\Application\DTO;

use App\Coaster\Domain\Model\Coaster;

final readonly class CoasterDTO
{
    private function __construct(
        private string $id,
        private int $personNumber,
        private int $clientNumber,
        private int $distanceLength,
        private string $fromDate,
        private string $toDate,
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

    public static function fromEntity(Coaster $entity): self
    {
        return new self(
            $entity->id,
            $entity->personNumber,
            $entity->clientNumber,
            $entity->distanceLength,
            $entity->timeRange->fromDate->format(\DateTimeInterface::ATOM),
            $entity->timeRange->toDate->format(\DateTimeInterface::ATOM),
        );
    }
}
