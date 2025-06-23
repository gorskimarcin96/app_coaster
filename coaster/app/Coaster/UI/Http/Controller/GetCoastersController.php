<?php

declare(strict_types=1);

namespace App\Coaster\UI\Http\Controller;

use App\Coaster\Application\DTO\CoasterDTO;
use App\Coaster\Application\Query\GetCoastersHandler\GetCoastersHandler;
use App\Coaster\Application\Query\GetCoastersHandler\GetCoastersQuery;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

final class GetCoastersController extends ResourceController
{
    public function __invoke(): ResponseInterface
    {
        /** @var CoasterRepository $repository */
        $repository = service('coasterRepository');
        $handler = new GetCoastersHandler($repository);
        $entities = $handler(new GetCoastersQuery());

        return $this->respond(
            array_map(static fn(Coaster $entity): array => CoasterDTO::fromEntity($entity)->toArray(), $entities),
            200,
        );
    }
}
