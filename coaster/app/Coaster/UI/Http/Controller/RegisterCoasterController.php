<?php

namespace App\Coaster\UI\Http\Controller;

use App\Coaster\Application\Command\RegisterCoasterCommand\RegisterCoasterCommand;
use App\Coaster\Application\Command\RegisterCoasterCommand\RegisterCoasterHandler;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Infrastructure\Events\EventRegistrar;
use CodeIgniter\Events\Events;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use InvalidArgumentException;

final class RegisterCoasterController extends ResourceController
{
    /**
     * @throws Exception
     */
    public function __invoke(): ResponseInterface
    {
        try {
            /** @var CoasterRepository $repository */
            $repository = service('coasterRepository');
            $handler = new RegisterCoasterHandler($repository);
            $id = $handler(RegisterCoasterCommand::fromArray((array)$this->request->getJSON()));

            Events::trigger(EventRegistrar::COASTER_CREATED, $id);

            return $this->respond(['id' => $id->getId()->toString()], 201);
        } catch (InvalidArgumentException $exception) {
            return $this->respond(['error' => $exception->getMessage()], 400);
        }
    }
}
