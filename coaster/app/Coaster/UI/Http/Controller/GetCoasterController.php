<?php

namespace App\Coaster\UI\Http\Controller;

use App\Coaster\Application\DTO\CoasterDTO;
use App\Coaster\Application\Query\GetCoasterByIdHandler\GetCoasterByIdHandler;
use App\Coaster\Application\Query\GetCoasterByIdHandler\GetCoasterByIdQuery;
use App\Coaster\Domain\Repository\CoasterRepository;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

final class GetCoasterController extends ResourceController
{
    public function __invoke(string $id): ResponseInterface
    {
        /** @var CoasterRepository $repository */
        $repository = service('coasterRepository');
        $handler = new GetCoasterByIdHandler($repository);
        $entity = $handler(new GetCoasterByIdQuery($id));

        return $entity
            ? $this->respond(CoasterDTO::fromEntity($entity)->toArray(), 200)
            : $this->respond([], 404);
    }
}
