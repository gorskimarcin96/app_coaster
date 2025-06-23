<?php

namespace App\Coaster\UI\Console\Commands;

use App\Coaster\Domain\AsyncRepository\CoasterRepository;
use App\Coaster\Domain\AsyncRepository\WagonRepository;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\CoasterStatus;
use App\Coaster\Domain\Query\GetWagonsQuery;
use App\Coaster\Domain\ValueObject\CoasterWagons;
use App\Coaster\Infrastructure\Helper\LogCoasterMonitor;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use DateTimeImmutable;
use React\EventLoop\Loop;

/**
 * Zarządzanie kolejkami i wagonami:
 * 1. system umożliwia rejestrację i edycję kolejek górskich oraz rejestrację i usuwanie wagonów przez API
 * 2. każda kolejka górska działa w określonych godzinach (od, do)
 * 3. każdy wagon musi wrócić przed końcem czasu działania kolejki górskiej
 * 4. wagony potrzebują 5 minut przerwy, zanim ponownie będą mogły działać po skończonej trasie
 *
 * Zarządzanie personelem (p):
 * 1. do obsługi każdej kolejki górskiej wymagany jest 1 p (np. sprzedawca biletów - nie jest to istotne)
 * 2. do obsługi każdego wagonu dodatkowo wymagane są 2 p (np. maszynista i mechanik - nie jest to istotne)
 * 3. jeśli w systemie brakuje odpowiedniej liczby p do obsługi kolejki lub wagonu, system informuje o tym oraz wylicza brakującą liczbę p
 * 4. jeśli w systemie jest za dużo p, system informuje o tym oraz wylicza nadmiarową liczbę p
 *
 * Zarządzanie klientami:
 * 1. system monitoruje liczbę klientów, których kolejka górska powinna obsłużyć w ciągu dnia
 * 2. jeśli kolejka nie będzie w stanie obsłużyć wszystkich klientów w ciągu dnia, system informuje o tym i wylicza, ile brakuje wagonów oraz personelu
 * 3. jeśli kolejka górska ma więcej mocy przerobowych niż wymagane, tj. obsłuży ponad dwukrotnie więcej klientów niż zaplanowano, system informuje o nadmiarowej liczbie wagonów i personelu
 *
 */
final class CoasterMonitor extends BaseCommand
{
    protected $group = 'App';
    protected $name = 'app:coaster:monitor';
    protected $description = 'Show live logs for the coaster system.';

    public function run(array $params): void
    {
        /** @var CoasterRepository $coasterRepository */
        $coasterRepository = service('coasterAsyncRepository');
        /** @var WagonRepository $wagonRepository */
        $wagonRepository = service('wagonAsyncRepository');

        Loop::addPeriodicTimer(
            1,
            static fn() => $coasterRepository->get()
                ->then(fn(array $coasters) => self::initData($coasters, $wagonRepository)),
        );
    }

    /**
     * @param Coaster[] $coasters
     */
    private static function initData(array $coasters, WagonRepository $wagonRepository): void
    {
        CLI::clearScreen();
        CLI::write((new DateTimeImmutable)->format('[Y-m-d H:i:s]'));

        if (!$coasters) {
            CLI::write('Brak danych');
        }

        foreach ($coasters as $coaster) {
            $wagonRepository
                ->get(new GetWagonsQuery($coaster->id))
                ->then(fn(array $wagons) => self::showStats(new CoasterWagons($coaster, $wagons)));
        }
    }

    private static function showStats(CoasterWagons $coasterWagons): void
    {
        $color = match ($coasterWagons->status()) {
            CoasterStatus::OK => 'green',
            CoasterStatus::EXCESS_WAGONS, CoasterStatus::EXCESS_PERSONNEL, CoasterStatus::MISSING_CLIENTS => 'yellow',
            default => 'red',
        };

        array_map(
            static fn(string $message) => CLI::write($message, $color),
            (new LogCoasterMonitor())->create($coasterWagons),
        );
    }
}
