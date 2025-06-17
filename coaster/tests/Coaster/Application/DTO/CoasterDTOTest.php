<?php

namespace Coaster\Application\DTO;

use App\Coaster\Application\DTO\CoasterDTO;
use PHPUnit\Framework\TestCase;

final class CoasterDTOTest extends TestCase
{
    public function testToArray(): void
    {
        $DTO = new CoasterDTO('e5f2640e-d832-4673-967b-0f69aa04eb85', 1, 2, 3, '2000-01-01', '2000-01-07');
        $this->assertSame(
            [
                'id' => 'e5f2640e-d832-4673-967b-0f69aa04eb85',
                'personNumber' => 1,
                'clientNumber' => 2,
                'distanceLength' => 3,
                'fromDate' => '2000-01-01',
                'toDate' => '2000-01-07',
            ],
            $DTO->toArray(),
        );
    }
}
