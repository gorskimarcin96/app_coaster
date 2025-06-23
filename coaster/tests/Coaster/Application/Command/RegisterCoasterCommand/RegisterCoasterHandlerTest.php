<?php

declare(strict_types=1);

namespace Coaster\Application\Command\RegisterCoasterCommand;

use App\Coaster\Application\Command\RegisterCoasterCommand\RegisterCoasterCommand;
use App\Coaster\Application\Command\RegisterCoasterCommand\RegisterCoasterHandler;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use CodeIgniter\Test\CIUnitTestCase;
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
                        && $entity->availablePersonnel === 3
                        && $entity->clientsPerDay === 20
                        && $entity->trackLengthInMeters === 100
                        && $entity->timeRange->from->format('H:i') === '08:00'
                        && $entity->timeRange->to->format('H:i') === '16:00',
                ),
            );

        $handler = new RegisterCoasterHandler($repository);
        $command = new RegisterCoasterCommand(3, 20, 100, '08:00', '16:00');

        $handler($command);
    }
}
