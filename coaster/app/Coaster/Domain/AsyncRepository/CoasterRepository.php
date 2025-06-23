<?php

namespace App\Coaster\Domain\AsyncRepository;

use App\Coaster\Domain\Model\Coaster;
use React\Promise\PromiseInterface;

interface CoasterRepository
{
    /**
     * @return PromiseInterface<Coaster[]>
     */
    public function get(): PromiseInterface;
}
