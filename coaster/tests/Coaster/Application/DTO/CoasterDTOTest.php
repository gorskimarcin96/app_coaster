<?php

namespace Coaster\Application\DTO;

use App\Coaster\Application\DTO\CoasterDTO;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CoasterDTOTest extends TestCase
{
    public function testToArray(): void
    {
        $entity = Coaster::fromPersistence(
            new CoasterId(Uuid::fromString('e5f2640e-d832-4673-967b-0f69aa04eb85')),
            1,
            2,
            3,
            new TimeRange(new DateTimeImmutable('2000-01-01'), new DateTimeImmutable('2000-01-07')),
        );
        $this->assertSame(
            [
                'id' => 'e5f2640e-d832-4673-967b-0f69aa04eb85',
                'personNumber' => 1,
                'clientNumber' => 2,
                'distanceLength' => 3,
                'fromDate' => '2000-01-01T00:00:00+00:00',
                'toDate' => '2000-01-07T00:00:00+00:00',
            ],
            CoasterDTO::fromEntity($entity)->toArray(),
        );
    }
}
