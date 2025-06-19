<?php

namespace App\Coaster\Domain\Service;

use App\Coaster\Domain\Exception\WagonCanNotRunException;
use App\Coaster\Domain\ValueObject\RidePlanner;
use Exception;

final class CoastersAndWagonsManger
{
    /**
     * @throws Exception
     */
    public function handle(
        RidePlanner $ridePlan,
    ): void {
        $ridePlan->isFeasible()
            ? $ridePlan->wagon->run($ridePlan->startTime, $ridePlan->calculateDurationWagonRide())
            : throw new WagonCanNotRunException($ridePlan->wagon->id);
    }
}
