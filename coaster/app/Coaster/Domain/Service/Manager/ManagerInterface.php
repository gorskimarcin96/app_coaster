<?php

namespace App\Coaster\Domain\Service\Manager;

use App\Coaster\Domain\Service\Notifier\Notifier;
use App\Coaster\Domain\ValueObject\CoasterWagons;

interface ManagerInterface
{
    public function handle(CoasterWagons $coasterWagons, Notifier $notifier): void;
}
