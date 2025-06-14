<?php

namespace App\Coaster\UI\Http\Controller;

use App\Coaster\Application\DTO\WagonDTO;
use App\Coaster\Application\Mapper\WagonMapper;
use App\Coaster\Application\Query\GetWagonsHandler\GetWagonsQuery;
use App\Coaster\Application\Query\GetWagonsHandler\GetWagonsHandler;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Infrastructure\Redis\WagonRepository;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Exception;

final class GetWagonsController extends ResourceController
{
    /**
     * @throws Exception
     */
    public function __invoke(string $coasterId): ResponseInterface
    {
        /** @var WagonRepository $repository */
        $repository = service('wagonRepository');
        $handler = new GetWagonsHandler($repository);
        $entities = $handler(new GetWagonsQuery($coasterId));

        return $this->respond(
            array_map(static fn(Wagon $entity): WagonDTO => WagonMapper::toDTO($entity), $entities),
            200,
        );
    }
}
