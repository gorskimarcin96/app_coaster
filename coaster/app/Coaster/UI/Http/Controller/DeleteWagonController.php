<?php

namespace App\Coaster\UI\Http\Controller;

use App\Coaster\Application\Command\DeleteWagonCommand\DeleteWagonCommand;
use App\Coaster\Application\Command\DeleteWagonCommand\DeleteWagonHandler;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Infrastructure\Redis\WagonRepository;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

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

            return $this->respond([], 200);
        } catch (PageNotFoundException $exception) {
            return $this->respond(['error' => $exception->getMessage()], 404);
        }
    }
}
