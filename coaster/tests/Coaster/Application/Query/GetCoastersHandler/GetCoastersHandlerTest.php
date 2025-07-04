<?php

declare(strict_types=1);

namespace Coaster\Application\Query\GetCoastersHandler;

use App\Coaster\Application\Query\GetCoastersHandler\GetCoastersHandler;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Query\GetCoastersQuery as DomainQuery;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\TimeRange;
use CodeIgniter\Test\CIUnitTestCase;
use DateTimeImmutable;
use Exception;

final class GetCoastersHandlerTest extends CIUnitTestCase
{
    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandle(): void
    {
        /** @var CoasterRepository $repository */
        $repository = $this->createMock(CoasterRepository::class);
        $repository->expects($this->once())
            ->method('getByQuery')
            ->with(new DomainQuery())
            ->willReturn(
                [
                    Coaster::register(1, 2, 10, new TimeRange(new DateTimeImmutable(), new DateTimeImmutable())),
                    Coaster::register(1, 2, 10, new TimeRange(new DateTimeImmutable(), new DateTimeImmutable())),
                    Coaster::register(1, 2, 10, new TimeRange(new DateTimeImmutable(), new DateTimeImmutable())),
                ],
            );

        $handler = new GetCoastersHandler($repository);
        $response = $handler();

        $this->assertCount(3, $response);
    }
}
