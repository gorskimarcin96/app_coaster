<?php

namespace Coaster\Domain\Service;

use App\Coaster\Domain\Exception\WagonCanNotRunException;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Service\CoastersAndWagonsManger;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\RidePlanner;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Exception;

class CoastersAndWagonsMangerTest extends TestCase
{
    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandle(): void
    {
        $wagon = $this->createMock(Wagon::class);
        $coaster = $this->createStub(Coaster::class);
        $startTime = new DateTimeImmutable('2000-01-01 12:00:00');
        $rideDuration = new DateInterval('PT10M');

        $wagon->expects($this->once())
            ->method('run')
            ->with($startTime, $rideDuration);

        $ridePlanner = $this->getMockBuilder(RidePlanner::class)
            ->setConstructorArgs([$wagon, $coaster, $startTime])
            ->onlyMethods(['isFeasible', 'calculateDurationWagonRide'])
            ->getMock();

        $ridePlanner->expects($this->once())
            ->method('isFeasible')
            ->willReturn(true);

        $ridePlanner->expects($this->once())
            ->method('calculateDurationWagonRide')
            ->willReturn($rideDuration);

        $manager = new CoastersAndWagonsManger();
        $manager->handle($ridePlanner);
    }

    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandleWhenThrowWagonCanNotRunException(): void
    {
        $wagon = Wagon::register(CoasterId::generate(), 1, 1);
        $coaster = $this->createStub(Coaster::class);
        $ridePlanner = $this->getMockBuilder(RidePlanner::class)
            ->setConstructorArgs([$wagon, $coaster, new DateTimeImmutable()])
            ->onlyMethods(['isFeasible'])
            ->getMock();

        $ridePlanner->expects($this->once())->method('isFeasible')->willReturn(false);

        $this->expectException(WagonCanNotRunException::class);

        $manager = new CoastersAndWagonsManger();
        $manager->handle($ridePlanner);
    }
}
