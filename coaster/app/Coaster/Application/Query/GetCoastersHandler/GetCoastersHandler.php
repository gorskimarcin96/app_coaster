<?php

namespace App\Coaster\Application\Query\GetCoastersHandler;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;

final readonly class GetCoastersHandler
{
    public function __construct(private CoasterRepository $repository)
    {
    }

    /**
     * @return Coaster[]
     */
    public function __invoke(GetCoastersQuery $query): array
    {
        return $this->repository->getByQuery($query);
    }
}
