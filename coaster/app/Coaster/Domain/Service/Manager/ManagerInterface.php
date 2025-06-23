<?php

namespace App\Coaster\Domain\Service\Manager;

use App\Coaster\Domain\Service\Notifier;
use App\Coaster\Domain\ValueObject\CoasterWagons;

interface ManagerInterface
{
    public function handle(CoasterWagons $coasterWagons, Notifier $notifier): void;
}
