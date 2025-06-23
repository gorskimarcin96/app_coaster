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
