<?php

namespace App\Coaster\Domain\Service\Manager;

use App\Coaster\Domain\Service\Notifier\Notifier;
use App\Coaster\Domain\ValueObject\CoasterWagons;

final readonly class ClientManager implements ManagerInterface
{
    /**
     * @throws \Exception
     */
    public function handle(CoasterWagons $coasterWagons, Notifier $notifier): void
    {
        if (!count($coasterWagons->wagons)) {
            return;
        }

        if ($coasterWagons->calculateMissingWagons() > 0) {
            $notifier->notify(
                sprintf(
                    'The coaster %s needs %s more wagons.',
                    $coasterWagons->coaster->id,
                    $coasterWagons->calculateMissingWagons(),
                ),
            );
        }

        if ($coasterWagons->calculateMissingPersonnel() > 0) {
            $notifier->notify(
                sprintf(
                    'The coaster %s needs %s more personnel.',
                    $coasterWagons->coaster->id,
                    $coasterWagons->calculateMissingPersonnel(),
                ),
            );
        }

        if ($coasterWagons->coaster->clientsPerDay <= $coasterWagons->countRideNumberOfClientInCoasterInDay() / 2) {
            if ($coasterWagons->calculateExcessWagons() > 0) {
                $notifier->notify(
                    sprintf(
                        'The coaster %s has %s wagons too many.',
                        $coasterWagons->coaster->id,
                        $coasterWagons->calculateExcessWagons(),
                    ),
                );
            }

            if ($coasterWagons->calculateExcessPersonnel() > 0) {
                $notifier->notify(
                    sprintf(
                        'The coaster %s has %s persons too many.',
                        $coasterWagons->coaster->id,
                        $coasterWagons->calculateExcessPersonnel(),
                    ),
                );
            }
        }
    }
}
