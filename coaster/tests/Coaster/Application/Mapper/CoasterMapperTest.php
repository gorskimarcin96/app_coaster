<?php

namespace Coaster\Application\Mapper;

use App\Coaster\Application\Mapper\CoasterMapper;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

final class CoasterMapperTest extends TestCase
{
    public function testToDTO(): void
    {
        $entity = Coaster::register(
            1,
            2,
            3,
            new TimeRange(new DateTimeImmutable('2020-01-01'), new DateTimeImmutable('2020-01-02')),
        );
        $DTO = CoasterMapper::toDTO($entity);

        $this->assertSame($entity->id->getId()->toString(), $DTO->id);
        $this->assertSame($entity->personNumber, $DTO->personNumber);
        $this->assertSame($entity->clientNumber, $DTO->clientNumber);
        $this->assertSame($entity->distanceLength, $DTO->distanceLength);
        $this->assertSame($entity->timeRange->fromDate->format(DateTimeInterface::ATOM), $DTO->fromDate);
        $this->assertSame($entity->timeRange->toDate->format(DateTimeInterface::ATOM), $DTO->toDate);
    }
}
