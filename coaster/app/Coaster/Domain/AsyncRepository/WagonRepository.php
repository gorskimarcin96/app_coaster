<?php

namespace App\Coaster\Domain\AsyncRepository;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Query\GetWagonsQuery;
use React\Promise\PromiseInterface;

interface WagonRepository
{
    /**
     * @return PromiseInterface<Coaster[]>
     */
    public function get(GetWagonsQuery $query): PromiseInterface;
}
