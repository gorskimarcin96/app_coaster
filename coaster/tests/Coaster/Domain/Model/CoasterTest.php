<?php

namespace Coaster\Domain\Model;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use CodeIgniter\Test\CIUnitTestCase;
use DateTimeImmutable;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;

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

    /**
     * @throws Exception
     */
    #[DataProvider('isOpenForDateTimeDataProvider')]
    public function testIsOpenForDateTime(bool $expected, string $dateTime, string $from, string $to): void
    {
        $entity = Coaster::register(
            1,
            1,
            1,
            new TimeRange(new DateTimeImmutable($from), new DateTimeImmutable($to)),
        );

        $this->assertSame($expected, $entity->isOpenForDateTime(new DateTimeImmutable($dateTime)));
    }

    public static function isOpenForDateTimeDataProvider(): array
    {
        return [
            'inside range' => [
                true,
                '2025-06-17 09:00',
                '2025-06-17 08:00',
                '2025-06-17 16:00',
            ],
            'before range' => [
                false,
                '2025-06-17 07:00',
                '2025-06-17 08:00',
                '2025-06-17 16:00',
            ],
            'after range' => [
                false,
                '2025-06-17 17:00',
                '2025-06-17 08:00',
                '2025-06-17 16:00',
            ],
            'exactly at to' => [
                true,
                '2025-06-17 16:00',
                '2025-06-17 08:00',
                '2025-06-17 16:00',
            ],
            'exactly at from' => [
                true,
                '2025-06-17 08:00',
                '2025-06-17 08:00',
                '2025-06-17 16:00',
            ],
        ];
    }
}
