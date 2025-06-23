<?php

namespace App\Coaster\Application\Query\GetWagonsHandler;

use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Query\GetWagonsQuery as DomainQuery;
use App\Coaster\Domain\Repository\WagonRepository;
use Exception;

final readonly class GetWagonsHandler
{
    public function __construct(private WagonRepository $repository)
    {
    }

    /**
     * @return Wagon[]
     * @throws Exception
     */
    public function __invoke(GetWagonsQuery $query): array
    {
        return $this->repository->getByQuery(new DomainQuery($query->coasterId));
    }
}
