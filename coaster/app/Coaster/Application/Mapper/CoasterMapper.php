<?php

namespace App\Coaster\Application\Mapper;

use App\Coaster\Application\DTO\CoasterDTO;
use App\Coaster\Domain\Model\Coaster;

final readonly class CoasterMapper
{
    public static function toDTO(Coaster $entity): CoasterDTO
    {
        return new CoasterDTO(
            $entity->id,
            $entity->personNumber,
            $entity->clientNumber,
            $entity->distanceLength,
            $entity->timeRange->fromDate->format(\DateTimeInterface::ATOM),
            $entity->timeRange->toDate->format(\DateTimeInterface::ATOM),
        );
    }
}
