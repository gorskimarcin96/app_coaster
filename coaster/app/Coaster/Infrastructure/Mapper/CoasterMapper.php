<?php

declare(strict_types=1);

namespace App\Coaster\Infrastructure\Mapper;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use JsonException;
use Ramsey\Uuid\Uuid;

final readonly class CoasterMapper
{
    /**
     * @throws JsonException
     */
    public static function toJSON(Coaster $entity): string
    {
        return json_encode(
            [
                'id' => $entity->id->getId()->toString(),
                'availablePersonnel' => $entity->availablePersonnel,
                'clientsPerDay' => $entity->clientsPerDay,
                'trackLengthInMeters' => $entity->trackLengthInMeters,
                'from' => $entity->timeRange->from->format(DateTimeInterface::ATOM),
                'to' => $entity->timeRange->to->format(DateTimeInterface::ATOM),
            ],
            JSON_THROW_ON_ERROR,
        );
    }

    /**
     * @throws Exception
     */
    public static function toDomain(string $json): Coaster
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return Coaster::fromPersistence(
            new CoasterId(Uuid::fromString($data['id'])),
            $data['availablePersonnel'],
            $data['clientsPerDay'],
            $data['trackLengthInMeters'],
            new TimeRange(new DateTimeImmutable($data['from']), new DateTimeImmutable($data['to'])),
        );
    }
}
