<?php

namespace Coaster\Application\Command\RegisterCoasterCommand;

use App\Coaster\Application\Command\RegisterCoasterCommand\RegisterCoasterCommand;
use App\Coaster\Application\Command\RegisterCoasterCommand\RegisterCoasterHandler;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use CodeIgniter\Test\CIUnitTestCase;
use DateTimeInterface;
use Exception;

final class RegisterCoasterHandlerTest extends CIUnitTestCase
{
    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandle(): void
    {
        $repository = $this->createMock(CoasterRepository::class);
        $repository->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(
                    fn(Coaster $entity): bool => $entity->id instanceof CoasterId
                        && $entity->personNumber === 3
                        && $entity->clientNumber === 20
                        && $entity->distanceLength === 100
                        && $entity->timeRange->getStart()->format(DateTimeInterface::ATOM) === '2000-01-01T00:00:00+00:00'
                        && $entity->timeRange->getEnd()->format(DateTimeInterface::ATOM) === '2000-01-07T00:00:00+00:00',
                ),
            );

        $handler = new RegisterCoasterHandler($repository);
        $command = new RegisterCoasterCommand(3, 20, 100, '01-01-2000', '07-01-2000');

        $handler($command);
    }
}
