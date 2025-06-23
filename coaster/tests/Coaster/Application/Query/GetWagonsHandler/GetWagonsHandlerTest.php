<?php

declare(strict_types=1);

namespace Coaster\Application\Query\GetWagonsHandler;

use App\Coaster\Application\Query\GetWagonsHandler\GetWagonsHandler;
use App\Coaster\Application\Query\GetWagonsHandler\GetWagonsQuery;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Repository\WagonRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use CodeIgniter\Test\CIUnitTestCase;
use Exception;

final class GetWagonsHandlerTest extends CIUnitTestCase
{
    /**
     * @throws Exception|\PHPUnit\Framework\MockObject\Exception
     */
    public function testHandle(): void
    {
        $coasterId = CoasterId::generate();

        /** @var WagonRepository $repository */
        $repository = $this->createMock(WagonRepository::class);
        $repository->expects($this->once())
            ->method('getByQuery')
            ->willReturn([
                Wagon::register($coasterId, 4, 2.3),
                Wagon::register($coasterId, 4, 2.3),
                Wagon::register($coasterId, 4, 2.3),
            ]);

        $handler = new GetWagonsHandler($repository);
        $response = $handler(new GetWagonsQuery($coasterId->getId()->toString()));

        $this->assertCount(3, $response);
    }
}
