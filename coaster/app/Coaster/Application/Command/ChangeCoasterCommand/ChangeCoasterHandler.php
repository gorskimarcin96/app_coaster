<?php

namespace App\Coaster\Application\Command\ChangeCoasterCommand;

use App\Coaster\Application\Command\Exception\EntityNotFoundException;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use Exception;
use Ramsey\Uuid\Uuid;

final readonly class ChangeCoasterHandler
{
    public function __construct(private CoasterRepository $repository)
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(ChangeCoasterCommand $command): void
    {
        $entity = $this->repository->get(new CoasterId(Uuid::fromString($command->id)))
            ?? throw new EntityNotFoundException('coaster');

        $entity = $entity->withUpdatedData(
            $command->personNumber,
            $command->clientNumber,
            new TimeRange(new \DateTimeImmutable($command->fromDate), new \DateTimeImmutable($command->toDate)),
        );

        $this->repository->update($entity);
    }
}
