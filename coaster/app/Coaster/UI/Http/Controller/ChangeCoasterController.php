<?php

namespace App\Coaster\UI\Http\Controller;

use App\Coaster\Application\Command\ChangeCoasterCommand\ChangeCoasterCommand;
use App\Coaster\Application\Command\ChangeCoasterCommand\ChangeCoasterHandler;
use App\Coaster\Application\Command\Exception\InvalidCommandArgumentException;
use App\Coaster\Domain\Repository\CoasterRepository;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

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

            return $this->respond(['id' => $id], 200);
        } catch (InvalidCommandArgumentException $exception) {
            return $this->respond(['error' => $exception->getMessage()], 400);
        }
    }
}
