<?php

declare(strict_types=1);

namespace App\Coaster\Application\Command\ChangeCoasterCommand;

use App\Coaster\Application\Command\Exception\InvalidCommandArgumentException;

final readonly class ChangeCoasterCommand
{
    public function __construct(
        public string $id,
        public int $availablePersonnel,
        public int $clientsPerDay,
        public string $from,
        public string $to,
    ) {
    }

    public static function fromArray(array $data): ChangeCoasterCommand
    {
        return new ChangeCoasterCommand(
            $data['id'] ?? throw new InvalidCommandArgumentException('id'),
            $data['availablePersonnel'] ?? throw new InvalidCommandArgumentException('availablePersonnel'),
            $data['clientsPerDay'] ?? throw new InvalidCommandArgumentException('clientsPerDay'),
            $data['from'] ?? throw new InvalidCommandArgumentException('from'),
            $data['to'] ?? throw new InvalidCommandArgumentException('to'),
        );
    }
}
