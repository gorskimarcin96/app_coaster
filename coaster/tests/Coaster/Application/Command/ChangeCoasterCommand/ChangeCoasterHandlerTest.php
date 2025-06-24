<?php

declare(strict_types=1);

namespace Coaster\Application\Command\ChangeCoasterCommand;

use App\Coaster\Application\Command\ChangeCoasterCommand\ChangeCoasterCommand;
use App\Coaster\Application\Command\ChangeCoasterCommand\ChangeCoasterHandler;
use App\Coaster\Application\Exception\EntityNotFoundException;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

final class ChangeCoasterHandlerTest extends TestCase
{
    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandle(): void
    {
        $entity = $this->createMock(Coaster::class);
        $entity->expects($this->once())
            ->method('withUpdatedData')
            ->with(3, 20, new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')));

        $repository = $this->createMock(CoasterRepository::class);
        $repository->method('get')
            ->with('0896d580-696a-4ac6-9cb1-4d57c97b79c8')
            ->willReturn($entity);

        $handler = new ChangeCoasterHandler($repository);
        $command = new ChangeCoasterCommand('0896d580-696a-4ac6-9cb1-4d57c97b79c8', 3, 20, '08:00', '16:00');

        $handler($command);
    }

    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandleWhenCoasterNotFound(): void
    {
        $this->expectException(EntityNotFoundException::class);

        $repository = $this->createMock(CoasterRepository::class);
        $repository->method('get')
            ->with('0896d580-696a-4ac6-9cb1-4d57c97b79c8')
            ->willReturn(null);

        $handler = new ChangeCoasterHandler($repository);
        $command = new ChangeCoasterCommand('0896d580-696a-4ac6-9cb1-4d57c97b79c8', 3, 20, '08:00', '16:00');

        $handler($command);
    }
}
