<?php

namespace App\Coaster\Infrastructure\Mapper;

use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use JsonException;
use Ramsey\Uuid\Uuid;
use TypeError;

final readonly class WagonMapper
{
    /**
     * @throws JsonException
     */
    public static function toJSON(Wagon $entity): string
    {
        return json_encode(
            [
                'id' => $entity->id->getId()->toString(),
                'coasterId' => $entity->coasterId->getId()->toString(),
                'numberOfPlaces' => $entity->numberOfPlaces,
                'speed' => $entity->speed,
                'startedAt' => $entity->startedAt?->format(DateTimeInterface::ATOM),
                'expectedReturnAt' => $entity->expectedReturnAt?->format(DateTimeInterface::ATOM),
            ],
            JSON_THROW_ON_ERROR,
        );
    }

    /**
     * @throws Exception
     */
    public static function toDomain(string $json): Wagon
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        try {
            return Wagon::fromPersistence(
                new WagonId(Uuid::fromString($data['id'])),
                new CoasterId(Uuid::fromString($data['coasterId'])),
                $data['numberOfPlaces'],
                $data['speed'],
                $data['startedAt'] ? new DateTimeImmutable($data['startedAt']) : null,
                $data['expectedReturnAt'] ? new DateTimeImmutable($data['expectedReturnAt']) : null,
            );
        } catch (TypeError $error) {
            dd($data);
        }
    }
}
