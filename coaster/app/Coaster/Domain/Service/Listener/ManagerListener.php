<?php

declare(strict_types=1);

namespace App\Coaster\Domain\Service\Listener;

use App\Coaster\Domain\Query\GetWagonsQuery;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\Service\Manager\ClientManager;
use App\Coaster\Domain\Service\Manager\PersonnelManager;
use App\Coaster\Domain\Service\Notifier\Notifier;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\CoasterWagons;
use App\Coaster\Infrastructure\Redis\WagonRepository;
use Exception;

final readonly class ManagerListener
{
    public function __construct(
        private CoasterRepository $coasterRepository,
        private WagonRepository $wagonRepository,
        private Notifier $notifier,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(CoasterId $coasterId): void
    {
        $coasterWagons = new CoasterWagons(
            $this->coasterRepository->get($coasterId),
            $this->wagonRepository->getByQuery(new GetWagonsQuery($coasterId->getId()->toString())),
        );

        foreach ([new ClientManager(), new PersonnelManager()] as $manager) {
            $manager->handle($coasterWagons, $this->notifier);
        }
    }
}
