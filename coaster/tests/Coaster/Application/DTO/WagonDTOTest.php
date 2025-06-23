<?php

namespace Coaster\Application\DTO;

use App\Coaster\Application\DTO\WagonDTO;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class WagonDTOTest extends TestCase
{
    public function testToArray(): void
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
            ],
            WagonDTO::fromEntity($entity)->toArray(),
        );
    }
}
