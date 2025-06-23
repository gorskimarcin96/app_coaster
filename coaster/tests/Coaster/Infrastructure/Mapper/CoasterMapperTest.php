<?php

declare(strict_types=1);

namespace Coaster\Infrastructure\Mapper;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use App\Coaster\Infrastructure\Mapper\CoasterMapper;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use JsonException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CoasterMapperTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToDomain(): void
    {
        $json = '{"id":"b22f1078-e38a-43bf-a3e6-391d3dfc8a77","availablePersonnel":1,"clientsPerDay":2,"trackLengthInMeters":3,"from":"2020-01-01T00:00:00+00:00","to":"2020-01-02T00:00:00+00:00"}';

        $entity = CoasterMapper::toDomain($json);

        $this->assertSame('b22f1078-e38a-43bf-a3e6-391d3dfc8a77', $entity->id->getId()->toString());
        $this->assertSame(1, $entity->availablePersonnel);
        $this->assertSame(2, $entity->clientsPerDay);
        $this->assertSame(3, $entity->trackLengthInMeters);
        $this->assertSame("2020-01-01T00:00:00+00:00", $entity->timeRange->from->format(DateTimeInterface::ATOM));
        $this->assertSame("2020-01-02T00:00:00+00:00", $entity->timeRange->to->format(DateTimeInterface::ATOM));
    }

    /**
     * @throws JsonException
     */
    public function testToJSON(): void
    {
        $entity = Coaster::fromPersistence(
            new CoasterId(Uuid::fromString('b22f1078-e38a-43bf-a3e6-391d3dfc8a77')),
            1,
            2,
            3,
            new TimeRange(new DateTimeImmutable('2020-01-01'), new DateTimeImmutable('2020-01-02')),
        );

        $this->assertSame(
            '{"id":"b22f1078-e38a-43bf-a3e6-391d3dfc8a77","availablePersonnel":1,"clientsPerDay":2,"trackLengthInMeters":3,"from":"2020-01-01T00:00:00+00:00","to":"2020-01-02T00:00:00+00:00"}',
            CoasterMapper::toJSON($entity),
        );
    }
}
