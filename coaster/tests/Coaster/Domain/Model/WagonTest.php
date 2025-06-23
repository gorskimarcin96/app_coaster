<?php

namespace Coaster\Domain\Model;

use App\Coaster\Domain\Exception\WagonAlreadyRunException;
use App\Coaster\Domain\Exception\WagonHasBreakException;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use DateInterval;
use DateTimeInterface;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class WagonTest extends TestCase
{
    public function testRegister(): void
    {
        $coasterId = CoasterId::generate();
        $entity = Wagon::register($coasterId, 1, 2.2);

        $this->assertIsString($entity->id->getId()->toString());
        $this->assertSame($coasterId->getId()->toString(), $entity->coasterId->getId()->toString());
        $this->assertSame(1, $entity->seats);
        $this->assertSame(2.2, $entity->speedInMetersPerSecond);
    }

    public function testFromPersistence(): void
    {
        $wagonId = WagonId::generate();
        $coasterId = CoasterId::generate();
        $entity = Wagon::fromPersistence($wagonId, $coasterId, 1, 2.2);

        $this->assertSame($wagonId->getId()->toString(), $entity->id->getId()->toString());
        $this->assertSame($coasterId->getId()->toString(), $entity->coasterId->getId()->toString());
        $this->assertSame(1, $entity->seats);
        $this->assertSame(2.2, $entity->speedInMetersPerSecond);
    }

    #[DataProvider('invalidArgumentExceptionDataProvider')]
    public function testInvalidArgumentException(int $seats, float $speedInMetersPerSecond): void
    {
        $this->expectException(InvalidArgumentException::class);

        Wagon::register(CoasterId::generate(), $seats, $speedInMetersPerSecond);
    }

    public static function invalidArgumentExceptionDataProvider(): array
    {
        return [
            'invalid number of places' => [
                -3,
                10.0,
            ],
            'invalid speedInMetersPerSecond' => [
                10,
                -4.2,
            ],
        ];
    }

    public function getBreakDuration(): void
    {
        $this->assertInstanceOf(
            DateInterval::class,
            Wagon::register(CoasterId::generate(), 1, 2.2)->getBreakDuration(),
        );
    }
}
