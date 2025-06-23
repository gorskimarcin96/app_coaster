<?php

declare(strict_types=1);

namespace App\Coaster\Application\DTO;

use DateTimeInterface;
use App\Coaster\Domain\Model\Coaster;

final readonly class CoasterDTO
{
    private function __construct(
        private string $id,
        private int $availablePersonnel,
        private int $clientsPerDay,
        private int $trackLengthInMeters,
        private string $from,
        private string $to,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'availablePersonnel' => $this->availablePersonnel,
            'clientsPerDay' => $this->clientsPerDay,
            'trackLengthInMeters' => $this->trackLengthInMeters,
            'from' => $this->from,
            'to' => $this->to,
        ];
    }

    public static function fromEntity(Coaster $entity): self
    {
        return new self(
            $entity->id->getId()->toString(),
            $entity->availablePersonnel,
            $entity->clientsPerDay,
            $entity->trackLengthInMeters,
            $entity->timeRange->from->format(DateTimeInterface::ATOM),
            $entity->timeRange->to->format(DateTimeInterface::ATOM),
        );
    }
}
