<?php

namespace App\Coaster\Infrastructure\Events;

use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\Service\Listener\ManagerListener;
use App\Coaster\Domain\Service\Notifier;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Infrastructure\Redis\WagonRepository;
use CodeIgniter\Events\Events;

final class EventRegistrar
{
    public const EVENTS_LIST = [
        self::COASTER_CREATED,
        self::COASTER_UPDATED,
        self::WAGON_CREATED,
        self::WAGON_DELETED,
    ];
    public const COASTER_CREATED = 'coaster.created';
    public const COASTER_UPDATED = 'coaster.updated';
    public const WAGON_CREATED = 'wagon.created';
    public const WAGON_DELETED = 'wagon.deleted';

    public static function register(): void
    {
        /** @var CoasterRepository $coasterRepository */
        $coasterRepository = service('coasterRepository');
        /** @var WagonRepository $wagonRepository */
        $wagonRepository = service('wagonRepository');
        /** @var Notifier $notifier */
        $notifier = service('notifier');
        $managerListener = new ManagerListener($coasterRepository, $wagonRepository, $notifier);

        array_map(
            static fn(string $eventName) => Events::on(
                $eventName,
                static fn(CoasterId $coasterId) => $managerListener->handle($coasterId),
            ),
            self::EVENTS_LIST,
        );
    }
}
