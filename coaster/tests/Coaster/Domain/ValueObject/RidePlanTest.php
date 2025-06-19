<?php

namespace Coaster\Domain\ValueObject;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\RidePlan;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RidePlanTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[DataProvider('calculateWagonEndTimeDataProvider')]
    public function testCalculateWagonEndTime(
        DateTimeImmutable $expected,
        DateTimeImmutable $startTime,
        float $speed,
        int $distanceLength,
    ): void {
        $coaster = Coaster::register(
            1,
            1,
            $distanceLength,
            new TimeRange(new DateTimeImmutable(), new DateTimeImmutable()),
        );
        $wagon = Wagon::register(CoasterId::generate(), 1, $speed);
        $ridePlan = new RidePlan($wagon, $coaster, $startTime);

        $this->assertSame(
            $expected->format(DateTimeInterface::ATOM),
            $ridePlan->calculateWagonEndTime()->format(DateTimeInterface::ATOM),
        );
    }

    public static function calculateWagonEndTimeDataProvider(): array
    {
        return [
            [new DateTimeImmutable('2000-01-01T00:00:02+00:00'), new DateTimeImmutable('2000-01-01'), 10.0, 10],
            [new DateTimeImmutable('2000-01-01T00:03:20+00:00'), new DateTimeImmutable('2000-01-01'), 10.0, 1000],
            [new DateTimeImmutable('2000-01-01T00:08:40+00:00'), new DateTimeImmutable('2000-01-01'), 10.0, 2600],
            [new DateTimeImmutable('2000-01-01T00:16:40+00:00'), new DateTimeImmutable('2000-01-01'), 10.0, 5000],
            [new DateTimeImmutable('2000-01-01T02:46:40+00:00'), new DateTimeImmutable('2000-01-01'), 0.2, 1000],
            [new DateTimeImmutable('2000-01-01T00:01:31+00:00'), new DateTimeImmutable('2000-01-01'), 22.2, 1000],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('calculateWagonEndTimeDataWithBreakDataProvider')]
    public function testCalculateWagonEndTimeWithBreak(
        DateTimeImmutable $expected,
        DateTimeImmutable $startTime,
        float $speed,
        int $distanceLength,
    ): void {
        $coaster = Coaster::register(
            1,
            1,
            $distanceLength,
            new TimeRange(new DateTimeImmutable(), new DateTimeImmutable()),
        );
        $wagon = Wagon::register(CoasterId::generate(), 1, $speed);
        $ridePlan = new RidePlan($wagon, $coaster, $startTime);

        $this->assertSame(
            $expected->format(DateTimeInterface::ATOM),
            $ridePlan->calculateWagonEndTimeWithBreak()->format(DateTimeInterface::ATOM),
        );
    }

    public static function calculateWagonEndTimeDataWithBreakDataProvider(): array
    {
        return [
            [new DateTimeImmutable('2000-01-01T00:05:02+00:00'), new DateTimeImmutable('2000-01-01'), 10.0, 10],
            [new DateTimeImmutable('2000-01-01T00:08:20+00:00'), new DateTimeImmutable('2000-01-01'), 10.0, 1000],
            [new DateTimeImmutable('2000-01-01T00:13:40+00:00'), new DateTimeImmutable('2000-01-01'), 10.0, 2600],
            [new DateTimeImmutable('2000-01-01T00:21:40+00:00'), new DateTimeImmutable('2000-01-01'), 10.0, 5000],
            [new DateTimeImmutable('2000-01-01T02:51:40+00:00'), new DateTimeImmutable('2000-01-01'), 0.2, 1000],
            [new DateTimeImmutable('2000-01-01T00:06:31+00:00'), new DateTimeImmutable('2000-01-01'), 22.2, 1000],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('isFeasibleDataProvider')]
    public function testIsFeasible(
        bool $expected,
        DateTimeImmutable $startTime,
        float $speed,
        int $distanceLength,
        DateTimeInterface $from,
        DateTimeInterface $to,
    ): void {
        $coaster = Coaster::register(1, 1, $distanceLength, new TimeRange($from, $to));
        $wagon = Wagon::register(CoasterId::generate(), 1, $speed);
        $ridePlan = new RidePlan($wagon, $coaster, $startTime);

        $this->assertSame($expected, $ridePlan->isFeasible());
    }

    public static function isFeasibleDataProvider(): array
    {
        return [
            'is opened' => [
                true,
                new DateTimeImmutable('2000-01-01'),
                10.0,
                10,
                new DateTimeImmutable('2000-01-01'),
                new DateTimeImmutable('2000-01-07'),
            ],
            'is closed' => [
                false,
                new DateTimeImmutable('2000-01-08'),
                10.0,
                10,
                new DateTimeImmutable('2000-01-01'),
                new DateTimeImmutable('2000-01-07'),
            ],
            'wagon can not back before end time' => [
                false,
                new DateTimeImmutable('2000-01-01T15:50:00+00:00'),
                7.5,
                2500,
                new DateTimeImmutable('2000-01-01T10:00:00+00:00'),
                new DateTimeImmutable('2000-01-01T16:00:00+00:00'),
            ],
        ];
    }
}
