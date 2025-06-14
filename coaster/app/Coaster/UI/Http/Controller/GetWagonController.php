<?php

namespace App\Coaster\UI\Http\Controller;

use App\Coaster\Application\Mapper\WagonMapper;
use App\Coaster\Application\Query\GetWagonByIdHandler\GetWagonByIdHandler;
use App\Coaster\Application\Query\GetWagonByIdHandler\GetWagonByIdQuery;
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

        $handler = new GetWagonByIdHandler();
        $entity = $handler(new GetWagonByIdQuery($coasterId, $wagonId));

        return $this->respond(WagonMapper::toDTO($entity), 200);
    }
}
