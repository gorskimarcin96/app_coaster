<?php

namespace Coaster\Domain\ValueObject;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\CoasterWagons;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CoasterWagonsTest extends TestCase
{
    #[DataProvider('requiredPersonalNumberDataProvider')]
    public function testRequiredPersonalNumber(int $expected, int $wagonNumber): void
    {
        $coaster = Coaster::register(1, 2, 10, new TimeRange(new DateTimeImmutable(), new DateTimeImmutable()));
        for ($i = 0; $i < $wagonNumber; $i++) {
            $wagons[] = Wagon::register(CoasterId::generate(), 1, 1);
        }

        $coasterWagons = new CoasterWagons($coaster, $wagons ?? []);

        $this->assertSame($expected, $coasterWagons->countRequiredPersonalNumber());
    }

    public static function requiredPersonalNumberDataProvider(): array
    {
        return [
            [1, 0],
            [3, 1],
            [21, 10],
        ];
    }
}
