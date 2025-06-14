<?php

namespace Coaster\Application\Query\GetWagonByIdHandler;

use App\Coaster\Application\Query\GetWagonByIdHandler\GetWagonByIdHandler;
use App\Coaster\Application\Query\GetWagonByIdHandler\GetWagonByIdQuery;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Repository\WagonRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use Exception;
use PHPUnit\Framework\TestCase;

final class GetWagonByIdHandlerTest extends TestCase
{
    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandle(): void
    {
        $coasterId = CoasterId::generate();
        $wagonId = WagonId::generate();
        $entity = Wagon::register($coasterId, 4, 2.3);

        /** @var WagonRepository $repository */
        $repository = $this->createMock(WagonRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with($coasterId, $wagonId)
            ->willReturn($entity);

        $handler = new GetWagonByIdHandler($repository);
        $response = $handler(new GetWagonByIdQuery($coasterId, $wagonId));

        $this->assertSame($entity, $response);
    }

    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandleWhenNotFound(): void
    {
        /** @var WagonRepository $repository */
        $repository = $this->createMock(WagonRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('2449fc75-f5bc-4895-bfe4-2e4fd16ac8a6', '36140022-4e5e-457a-90d3-f155b4856eb0')
            ->willReturn(null);

        $handler = new GetWagonByIdHandler($repository);
        $response = $handler(
            new GetWagonByIdQuery('2449fc75-f5bc-4895-bfe4-2e4fd16ac8a6', '36140022-4e5e-457a-90d3-f155b4856eb0'),
        );

        $this->assertNull($response);
    }
}
