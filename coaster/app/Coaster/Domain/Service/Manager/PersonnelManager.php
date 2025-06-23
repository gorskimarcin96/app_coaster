<?php

declare(strict_types=1);

namespace App\Coaster\Domain\Service\Manager;

use App\Coaster\Domain\Service\Notifier\Notifier;
use App\Coaster\Domain\ValueObject\CoasterWagons;

final readonly class PersonnelManager implements ManagerInterface
{
    public function handle(CoasterWagons $coasterWagons, Notifier $notifier): void
    {
        if ($coasterWagons->wagons === []) {
            return;
        }

        if (0 < $missingPersonnel = $coasterWagons->calculateMissingPersonnel()) {
            $notifier->notify(
                sprintf(
                    'The coaster %s needs %s persons.',
                    $coasterWagons->coaster->id,
                    $missingPersonnel,
                ),
            );
        }

        if (0 < $excessPersonnel = $coasterWagons->calculateExcessPersonnel()) {
            $notifier->notify(
                sprintf(
                    'The coaster %s has %s persons too many.',
                    $coasterWagons->coaster->id,
                    $excessPersonnel,
                ),
            );
        }
    }
}
