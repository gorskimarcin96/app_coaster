<?php

declare(strict_types=1);

namespace Coaster\Domain\Model;

use Iterator;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use CodeIgniter\Test\CIUnitTestCase;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;

final class CoasterTest extends CIUnitTestCase
{
    public function testRegister(): void
    {
        $availablePersonnel = 2;
        $clientsPerDay = 1;
        $trackLengthInMeters = 3;
        $from = new DateTimeImmutable('01-01-2000');
        $to = new DateTimeImmutable('07-01-2000');

        $entity = Coaster::register($availablePersonnel, $clientsPerDay, $trackLengthInMeters, new TimeRange($from, $to));

        $this->assertIsString($entity->id->getId()->toString());
        $this->assertSame($clientsPerDay, $entity->clientsPerDay);
        $this->assertSame($availablePersonnel, $entity->availablePersonnel);
        $this->assertSame($trackLengthInMeters, $entity->trackLengthInMeters);
        $this->assertSame($from, $entity->timeRange->from);
        $this->assertSame($to, $entity->timeRange->to);
    }

    public function testRegisterWithInvalidTimeRange(): void
    {
        $availablePersonnel = 2;
        $clientsPerDay = 1;
        $trackLengthInMeters = 3;
        $from = new DateTimeImmutable('07-01-2000');
        $to = new DateTimeImmutable('01-01-2000');

        $this->expectException(InvalidArgumentException::class);
        Coaster::register($availablePersonnel, $clientsPerDay, $trackLengthInMeters, new TimeRange($from, $to));
    }

    public function testFromPersistence(): void
    {
        $id = CoasterId::generate();
        $availablePersonnel = 2;
        $clientsPerDay = 1;
        $trackLengthInMeters = 3;
        $from = new DateTimeImmutable('01-01-2000');
        $to = new DateTimeImmutable('07-01-2000');

        $entity = Coaster::fromPersistence(
            $id,
            $availablePersonnel,
            $clientsPerDay,
            $trackLengthInMeters,
            new TimeRange($from, $to),
        );

        $this->assertSame($id, $entity->id);
        $this->assertSame($clientsPerDay, $entity->clientsPerDay);
        $this->assertSame($availablePersonnel, $entity->availablePersonnel);
        $this->assertSame($trackLengthInMeters, $entity->trackLengthInMeters);
        $this->assertSame($from, $entity->timeRange->from);
        $this->assertSame($to, $entity->timeRange->to);
    }

    public function testWithUpdatedData(): void
    {
        $entity = Coaster::fromPersistence(
            CoasterId::generate(),
            2,
            1,
            20,
            new TimeRange(new DateTimeImmutable('01-01-2000'), new DateTimeImmutable('01-01-2000')),
        );

        $availablePersonnel = 2;
        $clientsPerDay = 1;
        $from = new DateTimeImmutable('02-01-2000');
        $to = new DateTimeImmutable('05-01-2000');
        $entity = $entity->withUpdatedData($availablePersonnel, $clientsPerDay, new TimeRange($from, $to));

        $this->assertSame($clientsPerDay, $entity->clientsPerDay);
        $this->assertSame($availablePersonnel, $entity->availablePersonnel);
        $this->assertSame($from, $entity->timeRange->from);
        $this->assertSame($to, $entity->timeRange->to);
    }

    #[DataProvider('invalidArgumentExceptionDataProvider')]
    public function testInvalidArgumentException(int $availablePersonnel, int $clientsPerDay, int $trackLengthInMeters): void
    {
        $this->expectException(InvalidArgumentException::class);

        Coaster::register(
            $availablePersonnel,
            $clientsPerDay,
            $trackLengthInMeters,
            new TimeRange(new DateTimeImmutable(), new DateTimeImmutable()),
        );
    }

    public static function invalidArgumentExceptionDataProvider(): Iterator
    {
        yield 'invalid person number' => [
            -1,
            10,
            50000,
        ];
        yield 'invalid client number' => [
            10,
            -1,
            50000,
        ];
        yield 'invalid distance length' => [
            10,
            10,
            0,
        ];
    }
}
