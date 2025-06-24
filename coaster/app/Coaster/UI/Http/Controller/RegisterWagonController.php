<?php

declare(strict_types=1);

namespace App\Coaster\UI\Http\Controller;

use App\Coaster\Application\Command\RegisterWagonCommand\RegisterWagonCommand;
use App\Coaster\Application\Command\RegisterWagonCommand\RegisterWagonHandler;
use App\Coaster\Application\Exception\EntityNotFoundException;
use App\Coaster\Application\Exception\InvalidCommandArgumentException;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\Repository\WagonRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Infrastructure\Events\EventRegistrar;
use CodeIgniter\Events\Events;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use Ramsey\Uuid\Uuid;

final class RegisterWagonController extends ResourceController
{
    /**
     * @throws Exception
     */
    public function __invoke(string $coasterId): ResponseInterface
    {
        try {
            /** @var CoasterRepository $coasterRepository */
            $coasterRepository = service('coasterRepository');
            /** @var WagonRepository $wagonRepository */
            $wagonRepository = service('wagonRepository');

            $handler = new RegisterWagonHandler($coasterRepository, $wagonRepository);
            $id = $handler(
                RegisterWagonCommand::fromArray((array)$this->request->getJSON() + ['coasterId' => $coasterId]),
            );

            Events::trigger(EventRegistrar::WAGON_CREATED, new CoasterId(Uuid::fromString($coasterId)));

            return $this->respond(['id' => $id->getId()->toString()], 201);
        } catch (InvalidCommandArgumentException $exception) {
            return $this->respond(['error' => $exception->getMessage()], 400);
        } catch (EntityNotFoundException $exception) {
            return $this->respond(['error' => $exception->getMessage()], 404);
        }
    }
}
