<?php

namespace App\Coaster\Application\Command\RegisterWagonCommand;

use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\Repository\WagonRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use CodeIgniter\Exceptions\PageNotFoundException;
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
            ?? throw new PageNotFoundException('Coaster not found.');

        $entity = Wagon::register(
            $coaster->id,
            $command->numberOfPlaces,
            $command->speed,
        );

        $this->wagonRepository->save($entity);

        return $entity->id;
    }
}
