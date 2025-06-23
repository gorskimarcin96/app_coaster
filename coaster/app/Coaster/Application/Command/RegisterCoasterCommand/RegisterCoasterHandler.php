<?php

declare(strict_types=1);

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
            $command->availablePersonnel,
            $command->clientsPerDay,
            $command->trackLengthInMeters,
            new TimeRange(new DateTimeImmutable($command->from), new DateTimeImmutable($command->to)),
        );

        $this->repository->save($entity);

        return $entity->id;
    }
}
