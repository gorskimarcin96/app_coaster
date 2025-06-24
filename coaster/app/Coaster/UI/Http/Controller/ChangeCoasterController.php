<?php

declare(strict_types=1);

namespace App\Coaster\UI\Http\Controller;

use App\Coaster\Application\Command\ChangeCoasterCommand\ChangeCoasterCommand;
use App\Coaster\Application\Command\ChangeCoasterCommand\ChangeCoasterHandler;
use App\Coaster\Application\Exception\EntityNotFoundException;
use App\Coaster\Application\Exception\InvalidCommandArgumentException;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Infrastructure\Events\EventRegistrar;
use CodeIgniter\Events\Events;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use Ramsey\Uuid\Uuid;

final class ChangeCoasterController extends ResourceController
{
    /**
     * @throws Exception
     */
    public function __invoke(string $id): ResponseInterface
    {
        try {
            /** @var CoasterRepository $repository */
            $repository = service('coasterRepository');
            $handler = new ChangeCoasterHandler($repository);
            $handler(ChangeCoasterCommand::fromArray((array)$this->request->getJSON() + ['id' => $id]));

            Events::trigger(EventRegistrar::COASTER_UPDATED, new CoasterId(Uuid::fromString($id)));

            return $this->respond(['id' => $id], 200);
        } catch (InvalidCommandArgumentException $exception) {
            return $this->respond(['error' => $exception->getMessage()], 400);
        } catch (EntityNotFoundException $exception) {
            return $this->respond(['error' => $exception->getMessage()], 404);
        }
    }
}
