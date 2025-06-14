<?php

namespace App\Coaster\Application\Command\DeleteWagonCommand;

use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\Repository\WagonRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use CodeIgniter\Exceptions\PageNotFoundException;
use Exception;
use Ramsey\Uuid\Uuid;

final readonly class DeleteWagonHandler
{
    public function __construct(
        private CoasterRepository $coasterRepository,
        private WagonRepository $wagonRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(DeleteWagonCommand $command): void
    {
        $coaster = $this->coasterRepository->get(new CoasterId(Uuid::fromString($command->coasterId)))
            ?? throw new PageNotFoundException('Coaster not found.');

        $wagon = $this->wagonRepository->get($coaster->id, new WagonId(Uuid::fromString($command->wagonId)))
            ?? throw new PageNotFoundException('Wagon not found.');

        $this->wagonRepository->delete($wagon);
    }
}
