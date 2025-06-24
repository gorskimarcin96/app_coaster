<?php

declare(strict_types=1);

namespace App\Coaster\UI\Http\Controller;

use App\Coaster\Application\Command\DeleteWagonCommand\DeleteWagonCommand;
use App\Coaster\Application\Command\DeleteWagonCommand\DeleteWagonHandler;
use App\Coaster\Application\Exception\EntityNotFoundException;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Infrastructure\Events\EventRegistrar;
use App\Coaster\Infrastructure\Redis\WagonRepository;
use CodeIgniter\Events\Events;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use Ramsey\Uuid\Uuid;

final class DeleteWagonController extends ResourceController
{
    /**
     * @throws Exception
     */
    public function __invoke(string $coasterId, string $wagonId): ResponseInterface
    {
        try {
            /** @var CoasterRepository $coasterRepository */
            $coasterRepository = service('coasterRepository');
            /** @var WagonRepository $wagonRepository */
            $wagonRepository = service('wagonRepository');
            $handler = new DeleteWagonHandler($coasterRepository, $wagonRepository);
            $handler(new DeleteWagonCommand($coasterId, $wagonId));

            Events::trigger(EventRegistrar::WAGON_DELETED, new CoasterId(Uuid::fromString($coasterId)));

            return $this->respond([], 200);
        } catch (EntityNotFoundException $exception) {
            return $this->respond(['error' => $exception->getMessage()], 404);
        }
    }
}
