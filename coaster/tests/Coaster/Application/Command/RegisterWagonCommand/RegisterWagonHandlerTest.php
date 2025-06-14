<?php

namespace Coaster\Application\Command\RegisterWagonCommand;

use App\Coaster\Application\Command\RegisterWagonCommand\RegisterWagonHandler;
use App\Coaster\Application\Command\RegisterWagonCommand\RegisterWagonCommand;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class RegisterWagonHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        /** @var CoasterRepository $repository */
        $repository = service('coasterRepository');
        $entity = Coaster::register(
            1,
            2,
            10,
            new TimeRange(new DateTimeImmutable('01-01-2000'), new DateTimeImmutable('01-01-2000')),
        );
        $repository->save($entity);

        $handler = new RegisterWagonHandler();
        $command = new RegisterWagonCommand(9, 2.3, $entity->id->getId()->toString());
        $response = $handler($command);

        $this->assertIsString($response->getId()->toString());
    }
}
