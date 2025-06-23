<?php

declare(strict_types=1);

namespace Coaster\Application\Query\GetCoasterByIdHandler;

use App\Coaster\Application\Query\GetCoasterByIdHandler\GetCoasterByIdHandler;
use App\Coaster\Application\Query\GetCoasterByIdHandler\GetCoasterByIdQuery;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\TimeRange;
use CodeIgniter\Test\CIUnitTestCase;
use DateTimeImmutable;
use Exception;

final class GetCoasterByIdHandlerTest extends CIUnitTestCase
{
    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandle(): void
    {
        $entity = Coaster::register(1, 2, 10, new TimeRange(new DateTimeImmutable(), new DateTimeImmutable()));
        /** @var CoasterRepository $repository */
        $repository = $this->createMock(CoasterRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with($entity->id->getId()->toString())
            ->willReturn($entity);

        $handler = new GetCoasterByIdHandler($repository);
        $response = $handler(new GetCoasterByIdQuery($entity->id->getId()->toString()));

        $this->assertSame($entity, $response);
    }

    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandleWhenNotFound(): void
    {
        $repository = $this->createMock(CoasterRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('4c1403d9-43b7-4d14-82c0-4d58d80111c2')
            ->willReturn(null);

        $handler = new GetCoasterByIdHandler($repository);
        $response = $handler(new GetCoasterByIdQuery('4c1403d9-43b7-4d14-82c0-4d58d80111c2'));

        $this->assertNull($response);
    }
}
