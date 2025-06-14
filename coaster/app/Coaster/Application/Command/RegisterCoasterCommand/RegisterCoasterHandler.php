<?php

namespace App\Coaster\Application\Command\RegisterCoasterCommand;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
use Exception;

final readonly class RegisterCoasterHandler
{
    public function __construct(private CoasterRepository $repository)
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(RegisterCoasterCommand $command): CoasterId
    {
        $entity = Coaster::register(
            $command->personNumber,
            $command->clientNumber,
            $command->distanceLength,
            new TimeRange(new DateTimeImmutable($command->fromDate), new DateTimeImmutable($command->toDate)),
        );

        $this->repository->save($entity);

        return $entity->id;
    }
}
