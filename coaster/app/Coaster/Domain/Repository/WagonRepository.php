<?php

namespace App\Coaster\Domain\Repository;

use App\Coaster\Application\Query\GetWagonsHandler\GetWagonsQuery;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;

interface WagonRepository
{

    public function get(CoasterId $coasterId, WagonId $wagonId): Wagon;

    /**
     * @return Wagon[]
     */
    public function getByQuery(GetWagonsQuery $query): array;

    public function save(Wagon $entity): void;
}
