<?php

declare(strict_types=1);

namespace App\Coaster\Application\Command\ChangeCoasterCommand;

use App\Coaster\Application\Exception\EntityNotFoundException;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
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
            $command->availablePersonnel,
            $command->clientsPerDay,
            new TimeRange(new DateTimeImmutable($command->from), new DateTimeImmutable($command->to)),
        );

        $this->repository->update($entity);
    }
}
