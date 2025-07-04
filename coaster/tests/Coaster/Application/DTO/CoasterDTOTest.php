<?php

declare(strict_types=1);

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
            new TimeRange(new DateTimeImmutable('2000-01-01 08:00'), new DateTimeImmutable('2000-01-01 16:00')),
        );
        $this->assertSame(
            [
                'id' => 'e5f2640e-d832-4673-967b-0f69aa04eb85',
                'availablePersonnel' => 1,
                'clientsPerDay' => 2,
                'trackLengthInMeters' => 3,
                'from' => '2000-01-01T08:00:00+00:00',
                'to' => '2000-01-01T16:00:00+00:00',
            ],
            CoasterDTO::fromEntity($entity)->toArray(),
        );
    }
}
