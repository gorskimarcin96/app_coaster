<?php

namespace Coaster\Domain\Model;

use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use PHPUnit\Framework\TestCase;

final class WagonTest extends TestCase
{
    public function testRegister(): void
    {
        $coasterId = CoasterId::generate();
        $entity = Wagon::register($coasterId, 1, 2.2);

        $this->assertIsString($entity->id->getId()->toString());
        $this->assertSame($coasterId->getId()->toString(), $entity->coasterId->getId()->toString());
        $this->assertSame(1, $entity->numberOfPlaces);
        $this->assertSame(2.2, $entity->speed);
    }

    public function testFromPersistence(): void
    {
        $wagonId = WagonId::generate();
        $coasterId = CoasterId::generate();
        $entity = Wagon::fromPersistence($wagonId, $coasterId, 1, 2.2);

        $this->assertSame($wagonId->getId()->toString(), $entity->id->getId()->toString());
        $this->assertSame($coasterId->getId()->toString(), $entity->coasterId->getId()->toString());
        $this->assertSame(1, $entity->numberOfPlaces);
        $this->assertSame(2.2, $entity->speed);
    }
}
