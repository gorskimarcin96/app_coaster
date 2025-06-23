<?php

namespace App\Coaster\Infrastructure\Helper;

use App\Coaster\Domain\Exception\CoasterHasNotWagonsException;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\CoasterStatus;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\CoasterWagons;
use App\Coaster\Domain\ValueObject\TimeRange;

final readonly class LogCoasterMonitor
{
    /**
     * @return string[]
     */
    public function create(CoasterWagons $coasterWagons): array
    {
        $i = 0;

        return [
            $this->header($coasterWagons->coaster->id),
            $this->openingHours(++$i, $coasterWagons->coaster->timeRange),
            $this->numberOfWagons(++$i, $coasterWagons),
            $this->availablePersonnel(++$i, $coasterWagons),
            $this->numberOfClientsInDay(++$i, $coasterWagons->coaster),
            $this->status(++$i, $coasterWagons),
        ];
    }

    private function header(CoasterId $id): string
    {
        return sprintf('[Kolejka %s]', $id);
    }

    private function openingHours(int $position, TimeRange $timeRange): string
    {
        return sprintf(
            '%s. Godziny działania: %s - %s',
            $position,
            $timeRange->from->format('H:i'),
            $timeRange->from->format('H:i'),
        );
    }

    private function numberOfWagons(int $position, CoasterWagons $coasterWagons): string
    {
        try {
            $numberOfWagons = count($coasterWagons->wagons) . '/' . $coasterWagons->calculateNeedsWagons();
        } catch (CoasterHasNotWagonsException) {
            $numberOfWagons = 'Brakuje wagonów.';
        }

        return sprintf('%s. Liczba wagonów: %s', $position, $numberOfWagons);
    }

    private function availablePersonnel(int $position, CoasterWagons $coasterWagons): string
    {
        try {
            return sprintf(
                '%s. Dostępny personel: %s/%s',
                $position,
                $coasterWagons->coaster->availablePersonnel,
                $coasterWagons->calculateNeedsPersonnel(),
            );
        } catch (CoasterHasNotWagonsException) {
            return sprintf('%s. Dostępny personel: %s', $position, $coasterWagons->coaster->availablePersonnel);
        }
    }

    private function numberOfClientsInDay(int $position, Coaster $coaster): string
    {
        return sprintf('%s. Klienci dziennie: %s', $position, $coaster->clientsPerDay);
    }

    private function status(int $position, CoasterWagons $coasterWagons): string
    {
        $label = $coasterWagons->status() === CoasterStatus::OK ? 'Status' : 'Problem';

        try {
            $missingWagons = $coasterWagons->calculateMissingWagons();
            $missingPersonnel = $coasterWagons->calculateMissingPersonnel();
        } catch (CoasterHasNotWagonsException) {
            $missingWagons = 0;
            $missingPersonnel = 0;
        }

        $info = match ($coasterWagons->status()) {
            CoasterStatus::OK => 'OK',
            CoasterStatus::MISSING_CLIENTS => 'Brakuje klientów.',
            CoasterStatus::MISSING_WAGONS_AND_PERSONNEL => sprintf(
                'Brakuje %s pracowników i %s wagonów.',
                $missingWagons,
                $missingPersonnel,
            ),
            CoasterStatus::MISSING_WAGONS => $missingWagons
                ? sprintf('Brakuje wagonów %s.', $missingWagons)
                : 'Brakuje wagonów.',
            CoasterStatus::MISSING_PERSONNEL => sprintf(
                'Brakuje personelu %s.',
                $missingPersonnel,
            ),
            CoasterStatus::EXCESS_PERSONNEL => sprintf(
                'Nadmiar %s pracowników.',
                $coasterWagons->calculateExcessPersonnel(),
            ),
            CoasterStatus::EXCESS_WAGONS => sprintf(
                'Nadmiar %s wagonów.',
                $coasterWagons->calculateExcessWagons(),
            ),
        };

        return sprintf('%s. %s: %s', $position, $label, $info);
    }
}