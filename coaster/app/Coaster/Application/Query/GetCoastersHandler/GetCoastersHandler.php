<?php

declare(strict_types=1);

namespace App\Coaster\Application\Query\GetCoastersHandler;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\Query\GetCoastersQuery as DomainQuery;

final readonly class GetCoastersHandler
{
    public function __construct(private CoasterRepository $repository)
    {
    }

    /**
     * @return Coaster[]
     */
    public function __invoke(): array
    {
        return $this->repository->getByQuery(new DomainQuery());
    }
}
