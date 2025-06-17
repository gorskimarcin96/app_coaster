<?php

namespace App\Coaster\UI\Http\Controller;

use App\Coaster\Application\Command\Exception\EntityNotFoundException;
use App\Coaster\Application\Command\Exception\InvalidCommandArgumentException;
use App\Coaster\Application\Command\RegisterWagonCommand\RegisterWagonCommand;
use App\Coaster\Application\Command\RegisterWagonCommand\RegisterWagonHandler;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\Repository\WagonRepository;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

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

            return $this->respond(['id' => $id->getId()->toString()], 201);
        } catch (InvalidCommandArgumentException $exception) {
            return $this->respond(['error' => $exception->getMessage()], 400);
        } catch (EntityNotFoundException $exception) {
            return $this->respond(['error' => $exception->getMessage()], 404);
        }
    }
}
