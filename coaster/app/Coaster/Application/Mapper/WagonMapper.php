<?php

namespace App\Coaster\Application\Mapper;


use App\Coaster\Application\DTO\WagonDTO;
use App\Coaster\Domain\Model\Wagon;

final readonly class WagonMapper
{
    public static function toDTO(Wagon $entity): WagonDTO
    {
        return new WagonDTO(
            $entity->id,
            $entity->coasterId,
            $entity->numberOfPlaces,
            $entity->speed,
        );
    }
}
