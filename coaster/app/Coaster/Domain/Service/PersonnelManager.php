<?php

namespace App\Coaster\Domain\Service;

use App\Coaster\Domain\ValueObject\CoasterWagons;

final readonly class PersonnelManager
{
    /** @var CoasterWagons[] $coastersWagons */
    public function checkPersonsInCoasterSystem(array $coastersWagons, Notifier $notifier): void
    {
        foreach ($coastersWagons as $coasterWagons) {
            if ($coasterWagons->coaster->personNumber < $coasterWagons->requiredPersonalNumber()) {
                $notifier->notify(
                    sprintf(
                        'The coaster %s needs %s persons.',
                        $coasterWagons->coaster->id,
                        $coasterWagons->requiredPersonalNumber() - $coasterWagons->coaster->personNumber,
                    ),
                );
            }

            if ($coasterWagons->coaster->personNumber > $coasterWagons->requiredPersonalNumber()) {
                $notifier->notify(
                    sprintf(
                        'The coaster %s has %s persons too many.',
                        $coasterWagons->coaster->id,
                        $coasterWagons->coaster->personNumber - $coasterWagons->requiredPersonalNumber(),
                    ),
                );
            }
        }
    }
}
