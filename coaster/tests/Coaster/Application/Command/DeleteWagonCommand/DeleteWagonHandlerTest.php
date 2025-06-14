<?php

namespace Coaster\Application\Command\DeleteWagonCommand;

use App\Coaster\Application\Command\DeleteWagonCommand\DeleteWagonCommand;
use App\Coaster\Application\Command\DeleteWagonCommand\DeleteWagonHandler;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\Repository\WagonRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use CodeIgniter\Exceptions\PageNotFoundException;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class DeleteWagonHandlerTest extends TestCase
{
    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandle(): void
    {
        $coasterId = Uuid::uuid4();
        $wagonId = Uuid::uuid4();

        $coasterRepository = $this->createMock(CoasterRepository::class);
        $coasterRepository->expects($this->once())
            ->method('get')
            ->with($coasterId->toString())
            ->willReturn(
                Coaster::fromPersistence(
                    new CoasterId($coasterId),
                    0,
                    0,
                    0,
                    new TimeRange(new DateTimeImmutable(), new DateTimeImmutable()),
                ),
            );

        $wagon = $this->createMock(Wagon::class);
        $wagonRepository = $this->createMock(WagonRepository::class);
        $wagonRepository->expects($this->once())
            ->method('get')
            ->with($coasterId->toString(), $wagonId->toString())
            ->willReturn($wagon);
        $wagonRepository->expects($this->once())
            ->method('delete');

        $handler = new DeleteWagonHandler($coasterRepository, $wagonRepository);
        $command = new DeleteWagonCommand($coasterId->toString(), $wagonId->toString());
        $handler($command);
    }

    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandleWhenCoasterNotExists(): void
    {
        $coasterId = Uuid::uuid4();
        $wagonId = Uuid::uuid4();

        $coasterRepository = $this->createMock(CoasterRepository::class);
        $coasterRepository->expects($this->once())
            ->method('get')
            ->with($coasterId->toString())
            ->willReturn(null);

        $wagonRepository = $this->createMock(WagonRepository::class);

        $handler = new DeleteWagonHandler($coasterRepository, $wagonRepository);
        $command = new DeleteWagonCommand($coasterId->toString(), $wagonId->toString());

        $this->expectExceptionMessage('Coaster not found.');
        $this->expectException(PageNotFoundException::class);

        $handler($command);
    }

    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandleWhenWagonNotExists(): void
    {
        $coasterId = Uuid::uuid4();
        $wagonId = Uuid::uuid4();

        $coasterRepository = $this->createMock(CoasterRepository::class);
        $coasterRepository->expects($this->once())
            ->method('get')
            ->with($coasterId->toString())
            ->willReturn(
                Coaster::fromPersistence(
                    new CoasterId($coasterId),
                    0,
                    0,
                    0,
                    new TimeRange(new DateTimeImmutable(), new DateTimeImmutable()),
                ),
            );

        $wagonRepository = $this->createMock(WagonRepository::class);
        $wagonRepository->expects($this->once())
            ->method('get')
            ->with($coasterId->toString(), $wagonId->toString())
            ->willReturn(null);

        $handler = new DeleteWagonHandler($coasterRepository, $wagonRepository);
        $command = new DeleteWagonCommand($coasterId->toString(), $wagonId->toString());

        $this->expectExceptionMessage('Wagon not found.');
        $this->expectException(PageNotFoundException::class);

        $handler($command);
    }
}
