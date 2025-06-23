<?php

declare(strict_types=1);

namespace App\Coaster\Application\Query\GetCoasterByIdHandler;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use Ramsey\Uuid\Uuid;

final readonly class GetCoasterByIdHandler
{
    public function __construct(private CoasterRepository $repository)
    {
    }

    public function __invoke(GetCoasterByIdQuery $query): ?Coaster
    {
        return $this->repository->get(new CoasterId(Uuid::fromString($query->id)));
    }
}
