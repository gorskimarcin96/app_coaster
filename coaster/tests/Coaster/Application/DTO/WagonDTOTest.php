<?php

namespace Coaster\Application\DTO;

use App\Coaster\Application\DTO\WagonDTO;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class WagonDTOTest extends TestCase
{
    public function testMinModelToArray(): void
    {
        $entity = Wagon::fromPersistence(
            new WagonId(Uuid::fromString('835d6025-1ed4-4537-af02-33ab83895cd1')),
            new CoasterId(Uuid::fromString('b1958ecb-7eb2-4bb4-a040-b6117fae4382')),
            7,
            3.1,
        );
        $this->assertSame(
            [
                'id' => '835d6025-1ed4-4537-af02-33ab83895cd1',
                'coasterId' => 'b1958ecb-7eb2-4bb4-a040-b6117fae4382',
                'numberOfPlaces' => 7,
                'speed' => 3.1,
                'startedAt' => null,
                'expectedReturnAt' => null,
            ],
            WagonDTO::fromEntity($entity)->toArray(),
        );
    }

    public function testFullModelToArray(): void
    {
        $entity = Wagon::fromPersistence(
            new WagonId(Uuid::fromString('835d6025-1ed4-4537-af02-33ab83895cd1')),
            new CoasterId(Uuid::fromString('b1958ecb-7eb2-4bb4-a040-b6117fae4382')),
            7,
            3.1,
        )->run(new DateTimeImmutable('2000-01-01'), new DateInterval('PT30M'));
        $this->assertSame(
            [
                'id' => '835d6025-1ed4-4537-af02-33ab83895cd1',
                'coasterId' => 'b1958ecb-7eb2-4bb4-a040-b6117fae4382',
                'numberOfPlaces' => 7,
                'speed' => 3.1,
                'startedAt' => '2000-01-01T00:00:00+00:00',
                'expectedReturnAt' => '2000-01-01T00:30:00+00:00',
            ],
            WagonDTO::fromEntity($entity)->toArray(),
        );
    }

}
