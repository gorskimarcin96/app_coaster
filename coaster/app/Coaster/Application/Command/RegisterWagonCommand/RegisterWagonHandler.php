<?php

declare(strict_types=1);

namespace App\Coaster\Application\Command\RegisterWagonCommand;

use App\Coaster\Application\Exception\EntityNotFoundException;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\Repository\WagonRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use Exception;
use Ramsey\Uuid\Uuid;

final readonly class RegisterWagonHandler
{
    public function __construct(
        private CoasterRepository $coasterRepository,
        private WagonRepository $wagonRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(RegisterWagonCommand $command): WagonId
    {
        $coaster = $this->coasterRepository->get(new CoasterId(Uuid::fromString($command->coasterId)))
            ?? throw new EntityNotFoundException('coaster');

        $entity = Wagon::register(
            $coaster->id,
            $command->seats,
            $command->speedInMetersPerSecond,
        );

        $this->wagonRepository->save($entity);

        return $entity->id;
    }
}
