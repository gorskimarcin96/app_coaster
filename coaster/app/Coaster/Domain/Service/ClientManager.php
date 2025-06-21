<?php

namespace App\Coaster\Domain\Service;

use App\Coaster\Domain\ValueObject\CoasterWagons;
use Exception;

final readonly class ClientManager
{
    /**
     * @throws Exception
     * @var CoasterWagons[] $coastersWagons
     */
    public function checkClientsInCoasterSystem(array $coastersWagons, Notifier $notifier): void
    {
        foreach ($coastersWagons as $coasterWagons) {
            $numberOfClientsInDay = $coasterWagons->coaster->clientNumber;
            $rideNumberOfClientInCoasterInDay = $coasterWagons->countRideNumberOfClientInCoasterInDay();
            $missingClientsInDay = $numberOfClientsInDay - $rideNumberOfClientInCoasterInDay;
            $missingNumberOfWagons = ceil($missingClientsInDay / $coasterWagons->averageRideNumberOfClientWagonInDay());

            if ($numberOfClientsInDay > $rideNumberOfClientInCoasterInDay) {
                $notifier->notify(
                    sprintf(
                        'The coaster %s needs %s more wagons.',
                        $coasterWagons->coaster->id,
                        $missingNumberOfWagons,
                    ),
                );
            }

            $needsPersonnelNumberInDay = $coasterWagons->calculateNeedsPersonnelInCasterWithWagonsOfNumber(
                count($coasterWagons->wagons) + $missingNumberOfWagons,
            );

            if ($needsPersonnelNumberInDay > $coasterWagons->coaster->personNumber) {
                $notifier->notify(
                    sprintf(
                        'The coaster %s needs %s more personnel.',
                        $coasterWagons->coaster->id,
                        $needsPersonnelNumberInDay - $coasterWagons->coaster->personNumber,
                    ),
                );
            }

            if ($numberOfClientsInDay <= $rideNumberOfClientInCoasterInDay / 2) {
                $needsPersonnelOfNumber = $coasterWagons->calculateNeedsPersonnelInCasterWithWagonsOfNumber(
                    count($coasterWagons->wagons) + $missingNumberOfWagons,
                );

                if ($missingNumberOfWagons < 0) {
                    $notifier->notify(
                        sprintf(
                            'The coaster %s has %s wagons too many.',
                            $coasterWagons->coaster->id,
                            -1 * $missingNumberOfWagons,
                        ),
                    );
                }

                if ($coasterWagons->coaster->personNumber > $needsPersonnelOfNumber) {
                    $notifier->notify(
                        sprintf(
                            'The coaster %s has %s persons too many.',
                            $coasterWagons->coaster->id,
                            $coasterWagons->coaster->personNumber - $needsPersonnelOfNumber,
                        ),
                    );
                }
            }
        }
    }
}
