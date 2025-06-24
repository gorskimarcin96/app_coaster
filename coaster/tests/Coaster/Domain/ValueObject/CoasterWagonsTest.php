<?php

declare(strict_types=1);

namespace Coaster\Domain\ValueObject;

use Iterator;
use Exception;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\CoasterStatus;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\ValueObject\CoasterWagons;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CoasterWagonsTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[DataProvider('countRideNumberOfClientInCoasterInDayDataProvider')]
    public function testCountRideNumberOfClientInCoasterInDay(
        int $expected,
        int $wagonNumber,
        int $seats,
        int $speed,
        int $length,
    ): void {
        $coaster = Coaster::register(
            11,
            540,
            $length,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        for ($i = 0; $i < $wagonNumber; $i++) {
            $wagons[] = Wagon::register($coaster->id, $seats, $speed);
        }
        $coasterWagons = new CoasterWagons($coaster, $wagons ?? []);

        $this->assertSame($expected, $coasterWagons->countRideNumberOfClientInCoasterInDay());
    }

    public static function countRideNumberOfClientInCoasterInDayDataProvider(): Iterator
    {
        yield [540, 5, 20, 1, 4800];
        yield [1170, 5, 20, 1, 2000];
        yield [990, 5, 20, 2, 4800];
        yield [324, 3, 20, 1, 4800];
        yield [440, 5, 10, 2, 4800];
    }

    #[DataProvider('calculateNeedsPersonnelInCasterWithWagonsOfNumberDataProvider')]
    public function testCalculateNeedsPersonnelInCasterWithWagonsOfNumber(int $expected, int $wagonNumber): void
    {
        $coaster = Coaster::register(
            0,
            0,
            1,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        for ($i = 0; $i < $wagonNumber; $i++) {
            $wagons[] = Wagon::register($coaster->id, 2, 1);
        }

        $coasterWagons = new CoasterWagons($coaster, $wagons ?? []);

        $this->assertSame($expected, $coasterWagons->calculateNeedsPersonnelInCasterWithWagonsOfNumber($wagonNumber));
    }

    public static function calculateNeedsPersonnelInCasterWithWagonsOfNumberDataProvider(): Iterator
    {
        yield [1, 0];
        yield [3, 1];
        yield [21, 10];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('calculateMissingWagonsDataProvider')]
    public function testCalculateMissingWagons(int $expected, int $wagonNumber): void
    {
        $coaster = Coaster::register(
            0,
            540,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        for ($i = 0; $i < $wagonNumber; $i++) {
            $wagons[] = Wagon::register($coaster->id, 20, 1);
        }

        $coasterWagons = new CoasterWagons($coaster, $wagons ?? []);

        $this->assertSame($expected, $coasterWagons->calculateMissingWagons());

    }

    public static function calculateMissingWagonsDataProvider(): Iterator
    {
        yield [0, 5];
        yield [1, 4];
        yield [-1, 6];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('calculateMissingPersonnelDataProvider')]
    public function testCalculateMissingPersonnel(int $expected, int $personnelNumber): void
    {
        $coaster = Coaster::register(
            $personnelNumber,
            540,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $coasterWagons = new CoasterWagons($coaster, $wagons);

        $this->assertSame($expected, $coasterWagons->calculateMissingPersonnel());

    }

    public static function calculateMissingPersonnelDataProvider(): Iterator
    {
        yield [0, 11];
        yield [1, 10];
        yield [-1, 12];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('calculateExcessWagonsDataProvider')]
    public function testCalculateExcessWagons(int $expected, int $wagonNumber): void
    {
        $coaster = Coaster::register(
            0,
            540,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        for ($i = 0; $i < $wagonNumber; $i++) {
            $wagons[] = Wagon::register($coaster->id, 20, 1);
        }

        $coasterWagons = new CoasterWagons($coaster, $wagons ?? []);

        $this->assertSame($expected, $coasterWagons->calculateExcessWagons());

    }

    public static function calculateExcessWagonsDataProvider(): Iterator
    {
        yield [0, 5];
        yield [-1, 4];
        yield [1, 6];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('calculateExcessPersonnelDataProvider')]
    public function testCalculateExcessPersonnel(int $expected, int $personnelNumber): void
    {
        $coaster = Coaster::register(
            $personnelNumber,
            540,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $coasterWagons = new CoasterWagons($coaster, $wagons);

        $this->assertSame($expected, $coasterWagons->calculateExcessPersonnel());

    }

    public static function calculateExcessPersonnelDataProvider(): Iterator
    {
        yield [0, 11];
        yield [-1, 10];
        yield [1, 12];
    }

    /**
     * @throws Exception
     */
    public function testCalculateNeedsWagons(): void
    {
        $coaster = Coaster::register(
            0,
            540,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $coasterWagons = new CoasterWagons($coaster, $wagons);

        $this->assertSame(5, $coasterWagons->calculateNeedsWagons());

    }

    /**
     * @throws Exception
     */
    public function testCalculateNeedsPersonnel(): void
    {
        $coaster = Coaster::register(
            11,
            540,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $coasterWagons = new CoasterWagons($coaster, $wagons);

        $this->assertSame(11, $coasterWagons->calculateNeedsPersonnel());
    }

    /**
     * @throws Exception
     */
    public function testStatusExcessWagons(): void
    {
        $coaster = Coaster::register(
            11,
            270,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $coasterWagons = new CoasterWagons($coaster, $wagons);

        $this->assertSame(CoasterStatus::EXCESS_WAGONS, $coasterWagons->status());
    }

    /**
     * @throws Exception
     */
    public function testStatusMissingClients(): void
    {
        $coaster = Coaster::register(
            11,
            0,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $coasterWagons = new CoasterWagons($coaster, []);

        $this->assertSame(CoasterStatus::MISSING_CLIENTS, $coasterWagons->status());
    }

    /**
     * @throws Exception
     */
    public function testStatusMissingWagonsAndPersonnel(): void
    {
        $coaster = Coaster::register(
            11,
            600,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $coasterWagons = new CoasterWagons($coaster, $wagons);

        $this->assertSame(CoasterStatus::MISSING_WAGONS_AND_PERSONNEL, $coasterWagons->status());
    }

    /**
     * @throws Exception
     */
    public function testStatusMissingWagons(): void
    {
        $coaster = Coaster::register(
            11,
            540,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $coasterWagons = new CoasterWagons($coaster, $wagons);

        $this->assertSame(CoasterStatus::MISSING_WAGONS, $coasterWagons->status());
    }


    /**
     * @throws Exception
     */
    public function testStatusMissingPersonnel(): void
    {
        $coaster = Coaster::register(
            10,
            540,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $coasterWagons = new CoasterWagons($coaster, $wagons);

        $this->assertSame(CoasterStatus::MISSING_PERSONNEL, $coasterWagons->status());
    }

    /**
     * @throws Exception
     */
    public function testStatusExcessPersonnel(): void
    {
        $coaster = Coaster::register(
            12,
            540,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $coasterWagons = new CoasterWagons($coaster, $wagons);

        $this->assertSame(CoasterStatus::EXCESS_PERSONNEL, $coasterWagons->status());
    }

    /**
     * @throws Exception
     */
    public function testStatusOK(): void
    {
        $coaster = Coaster::register(
            11,
            540,
            4800,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $coasterWagons = new CoasterWagons($coaster, $wagons);

        $this->assertSame(CoasterStatus::OK, $coasterWagons->status());
    }
}
