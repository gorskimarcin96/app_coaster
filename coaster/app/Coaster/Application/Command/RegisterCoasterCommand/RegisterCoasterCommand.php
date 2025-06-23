<?php

declare(strict_types=1);

namespace App\Coaster\Application\Command\RegisterCoasterCommand;

use App\Coaster\Application\Command\Exception\InvalidCommandArgumentException;

final readonly class RegisterCoasterCommand
{
    public function __construct(
        public int $availablePersonnel,
        public int $clientsPerDay,
        public int $trackLengthInMeters,
        public string $from,
        public string $to,
    ) {
    }

    public static function fromArray(array $data): RegisterCoasterCommand
    {
        return new RegisterCoasterCommand(
            $data['availablePersonnel'] ?? throw new InvalidCommandArgumentException('availablePersonnel'),
            $data['clientsPerDay'] ?? throw new InvalidCommandArgumentException('clientsPerDay'),
            $data['trackLengthInMeters'] ?? throw new InvalidCommandArgumentException('trackLengthInMeters'),
            $data['from'] ?? throw new InvalidCommandArgumentException('from'),
            $data['to'] ?? throw new InvalidCommandArgumentException('to'),
        );
    }
}
