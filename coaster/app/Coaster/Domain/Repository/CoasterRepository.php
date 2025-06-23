<?php

namespace App\Coaster\Domain\Repository;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Query\GetCoastersQuery;
use App\Coaster\Domain\ValueObject\CoasterId;

interface CoasterRepository
{
    public function get(CoasterId $id): ?Coaster;

    /**
     * @return Coaster[]
     */
    public function getByQuery(GetCoastersQuery $query): array;

    public function save(Coaster $entity): void;

    public function update(Coaster $entity): void;
}
