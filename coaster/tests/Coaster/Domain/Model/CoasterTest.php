<?php

namespace Coaster\Domain\Model;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use CodeIgniter\Test\CIUnitTestCase;
use DateTimeImmutable;
use InvalidArgumentException;

final class CoasterTest extends CIUnitTestCase
{
    public function testRegister(): void
    {
        $personNumber = 2;
        $clientNumber = 1;
        $distanceLength = 3;
        $from = new DateTimeImmutable('01-01-2000');
        $to = new DateTimeImmutable('07-01-2000');

        $entity = Coaster::register($personNumber, $clientNumber, $distanceLength, new TimeRange($from, $to));

        $this->assertIsString($entity->id->getId()->toString());
        $this->assertSame($clientNumber, $entity->clientNumber);
        $this->assertSame($personNumber, $entity->personNumber);
        $this->assertSame($distanceLength, $entity->distanceLength);
        $this->assertSame($from, $entity->timeRange->fromDate);
        $this->assertSame($to, $entity->timeRange->toDate);
    }

    public function testRegisterWithInvalidTimeRange(): void
    {
        $personNumber = 2;
        $clientNumber = 1;
        $distanceLength = 3;
        $from = new DateTimeImmutable('07-01-2000');
        $to = new DateTimeImmutable('01-01-2000');

        $this->expectException(InvalidArgumentException::class);
        Coaster::register($personNumber, $clientNumber, $distanceLength, new TimeRange($from, $to));
    }

    public function testFromPersistence(): void
    {
        $id = CoasterId::generate();
        $personNumber = 2;
        $clientNumber = 1;
        $distanceLength = 3;
        $from = new DateTimeImmutable('01-01-2000');
        $to = new DateTimeImmutable('07-01-2000');

        $entity = Coaster::fromPersistence(
            $id,
            $personNumber,
            $clientNumber,
            $distanceLength,
            new TimeRange($from, $to),
        );

        $this->assertSame($id, $entity->id);
        $this->assertSame($clientNumber, $entity->clientNumber);
        $this->assertSame($personNumber, $entity->personNumber);
        $this->assertSame($distanceLength, $entity->distanceLength);
        $this->assertSame($from, $entity->timeRange->fromDate);
        $this->assertSame($to, $entity->timeRange->toDate);
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

        $personNumber = 2;
        $clientNumber = 1;
        $from = new DateTimeImmutable('02-01-2000');
        $to = new DateTimeImmutable('05-01-2000');
        $entity = $entity->withUpdatedData($personNumber, $clientNumber, new TimeRange($from, $to));

        $this->assertSame($clientNumber, $entity->clientNumber);
        $this->assertSame($personNumber, $entity->personNumber);
        $this->assertSame($from, $entity->timeRange->fromDate);
        $this->assertSame($to, $entity->timeRange->toDate);
    }
}
