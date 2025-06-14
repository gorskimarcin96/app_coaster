<?php

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
        $json = '{"id":"b22f1078-e38a-43bf-a3e6-391d3dfc8a77","personNumber":1,"clientNumber":2,"distanceLength":3,"fromDate":"2020-01-01T00:00:00+00:00","toDate":"2020-01-02T00:00:00+00:00"}';

        $entity = CoasterMapper::toDomain($json);

        $this->assertSame('b22f1078-e38a-43bf-a3e6-391d3dfc8a77', $entity->id->getId()->toString());
        $this->assertSame(1, $entity->personNumber);
        $this->assertSame(2, $entity->clientNumber);
        $this->assertSame(3, $entity->distanceLength);
        $this->assertSame("2020-01-01T00:00:00+00:00", $entity->timeRange->fromDate->format(DateTimeInterface::ATOM));
        $this->assertSame("2020-01-02T00:00:00+00:00", $entity->timeRange->toDate->format(DateTimeInterface::ATOM));
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
            '{"id":"b22f1078-e38a-43bf-a3e6-391d3dfc8a77","personNumber":1,"clientNumber":2,"distanceLength":3,"fromDate":"2020-01-01T00:00:00+00:00","toDate":"2020-01-02T00:00:00+00:00"}',
            CoasterMapper::toJSON($entity),
        );
    }
}
