<?php

namespace App\Coaster\Application\Command\RegisterCoasterCommand;

use App\Coaster\Application\Command\Exception\InvalidCommandArgumentException;

final readonly class RegisterCoasterCommand
{
    public function __construct(
        public int $personNumber,
        public int $clientNumber,
        public int $distanceLength,
        public string $fromDate,
        public string $toDate,
    ) {
    }

    public static function fromArray(array $data): RegisterCoasterCommand
    {
        return new RegisterCoasterCommand(
            $data['personNumber'] ?? throw new InvalidCommandArgumentException('personNumber'),
            $data['clientNumber'] ?? throw new InvalidCommandArgumentException('clientNumber'),
            $data['distanceLength'] ?? throw new InvalidCommandArgumentException('distanceLength'),
            $data['fromDate'] ?? throw new InvalidCommandArgumentException('fromDate'),
            $data['toDate'] ?? throw new InvalidCommandArgumentException('toDate'),
        );
    }
}
