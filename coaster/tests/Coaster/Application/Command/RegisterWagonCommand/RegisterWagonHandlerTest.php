<?php

declare(strict_types=1);

namespace Coaster\Application\Command\RegisterWagonCommand;

use App\Coaster\Application\Command\RegisterWagonCommand\RegisterWagonCommand;
use App\Coaster\Application\Command\RegisterWagonCommand\RegisterWagonHandler;
use App\Coaster\Application\Exception\EntityNotFoundException;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\Repository\WagonRepository;
use App\Coaster\Domain\ValueObject\TimeRange;
use App\Coaster\Domain\ValueObject\WagonId;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

final class RegisterWagonHandlerTest extends TestCase
{
    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandle(): void
    {
        $coaster = Coaster::register(1, 2, 10, new TimeRange(new DateTimeImmutable(), new DateTimeImmutable()));

        /** @var CoasterRepository $coasterRepository */
        $coasterRepository = $this->createMock(CoasterRepository::class);
        $coasterRepository->expects($this->once())
            ->method('get')
            ->willReturn($coaster);

        /** @var WagonRepository $wagonRepository */
        $wagonRepository = $this->createMock(WagonRepository::class);
        $wagonRepository->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(
                    fn(Wagon $entity): bool => $entity->id instanceof WagonId
                        && $entity->coasterId === $coaster->id
                        && $entity->speedInMetersPerSecond === 2.3
                        && $entity->seats === 9,
                ),
            );

        $handler = new RegisterWagonHandler($coasterRepository, $wagonRepository);
        $command = new RegisterWagonCommand(9, 2.3, $coaster->id->getId()->toString());

        $handler($command);
    }

    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandleWhenCoasterNotFound(): void
    {
        /** @var CoasterRepository $coasterRepository */
        $coasterRepository = $this->createMock(CoasterRepository::class);
        $coasterRepository->expects($this->once())
            ->method('get')
            ->willReturn(null);

        /** @var WagonRepository $wagonRepository */
        $wagonRepository = $this->createMock(WagonRepository::class);

        $handler = new RegisterWagonHandler($coasterRepository, $wagonRepository);
        $command = new RegisterWagonCommand(9, 2.3, 'fde7b0ea-5fed-4fa1-8927-de93998b79d0');

        $this->expectException(EntityNotFoundException::class);

        $handler($command);
    }
}
