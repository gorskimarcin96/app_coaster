<?php

declare(strict_types=1);

namespace App\Coaster\UI\Http\Controller;

use App\Coaster\Application\DTO\WagonDTO;
use App\Coaster\Application\Query\GetWagonByIdHandler\GetWagonByIdHandler;
use App\Coaster\Application\Query\GetWagonByIdHandler\GetWagonByIdQuery;
use App\Coaster\Domain\Repository\WagonRepository;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

final class GetWagonController extends ResourceController
{
    /**
     * @throws Exception
     */
    public function __invoke(string $coasterId, string $wagonId): ResponseInterface
    {
        /** @var WagonRepository $repository */
        $repository = service('wagonRepository');
        $handler = new GetWagonByIdHandler($repository);
        $entity = $handler(new GetWagonByIdQuery($coasterId, $wagonId));

        return $entity
            ? $this->respond(WagonDTO::fromEntity($entity)->toArray(), 200)
            : $this->respond([], 404);
    }
}
