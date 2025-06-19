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

    #[DataProvider('invalidArgumentExceptionDataProvider')]
    public function testInvalidArgumentException(int $numberOfPlaces, float $speed): void
    {
        $this->expectException(InvalidArgumentException::class);

        Wagon::register(CoasterId::generate(), $numberOfPlaces, $speed);
    }

    public static function invalidArgumentExceptionDataProvider(): array
    {
        return [
            'invalid number of places' => [
                -3,
                10.0,
            ],
            'invalid speed' => [
                10,
                -4.2,
            ],
        ];
    }

    public function testRun(): void
    {
        $entity = Wagon::register(CoasterId::generate(), 1, 2.2)
            ->run(new \DateTimeImmutable('01-01-2000'), new DateInterval('PT30M'));

        $this->assertSame(
            (new \DateTimeImmutable('01-01-2000'))->format(DateTimeInterface::ATOM),
            $entity->startedAt->format(DateTimeInterface::ATOM),
        );
        $this->assertSame(
            (new \DateTimeImmutable('01-01-2000 00:30'))->format(DateTimeInterface::ATOM),
            $entity->expectedReturnAt->format(DateTimeInterface::ATOM),
        );
    }

    public function testRunWhenWagonAlreadyRun(): void
    {
        $this->expectException(WagonAlreadyRunException::class);

        Wagon::register(CoasterId::generate(), 1, 2.2)
            ->run(new \DateTimeImmutable('01-01-2000'), new DateInterval('PT30M'))
            ->run(new \DateTimeImmutable('01-01-2000 00:05'), new DateInterval('PT30M'));
    }

    public function testRunWhenWagonHasBreak(): void
    {
        $this->expectException(WagonHasBreakException::class);

        Wagon::register(CoasterId::generate(), 1, 2.2)
            ->run(new \DateTimeImmutable('01-01-2000'), new DateInterval('PT30M'))
            ->run(new \DateTimeImmutable('01-01-2000 00:32'), new DateInterval('PT30M'));
    }

    public function getBreakDuration(): void
    {
        $this->assertInstanceOf(
            DateInterval::class,
            Wagon::register(CoasterId::generate(), 1, 2.2)->getBreakDuration(),
        );
    }

    public function testIsRunningAtWhenIsNotRun(): void
    {
        $entity = Wagon::register(CoasterId::generate(), 1, 2.2);

        $this->assertFalse($entity->isRunningAt(new \DateTimeImmutable()));
    }

    public function testIsRunningAtWhenIsRun(): void
    {
        $entity = Wagon::register(CoasterId::generate(), 1, 2.2)
            ->run(new \DateTimeImmutable(), new DateInterval('PT30M'));

        $this->assertTrue($entity->isRunningAt(new \DateTimeImmutable()));
    }

    public function testIsRunningAtWhenIsBack(): void
    {
        $entity = Wagon::register(CoasterId::generate(), 1, 2.2)
            ->run(new \DateTimeImmutable('2000-01-01'), new DateInterval('PT30M'));

        $this->assertFalse($entity->isRunningAt(new \DateTimeImmutable('2000-01-01 00:30:01')));
    }
}
