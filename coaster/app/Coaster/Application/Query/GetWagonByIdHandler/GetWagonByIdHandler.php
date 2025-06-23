<?php

declare(strict_types=1);

namespace App\Coaster\Application\Query\GetWagonByIdHandler;

use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Repository\WagonRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use Exception;
use Ramsey\Uuid\Uuid;

final readonly class GetWagonByIdHandler
{
    public function __construct(private WagonRepository $repository)
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(GetWagonByIdQuery $query): ?Wagon
    {
        return $this->repository->get(
            new CoasterId(Uuid::fromString($query->coasterId)),
            new WagonId(Uuid::fromString($query->wagonId)),
        );
    }
}
