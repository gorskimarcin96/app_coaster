<?php

namespace App\Coaster\Application\Command\ChangeCoasterCommand;

use App\Coaster\Application\Command\Exception\InvalidCommandArgumentException;

final readonly class ChangeCoasterCommand
{
    public function __construct(
        public string $id,
        public int $personNumber,
        public int $clientNumber,
        public string $fromDate,
        public string $toDate,
    ) {
    }

    public static function fromArray(array $data): ChangeCoasterCommand
    {
        return new ChangeCoasterCommand(
            $data['id'] ?? throw new InvalidCommandArgumentException('id'),
            $data['personNumber'] ?? throw new InvalidCommandArgumentException('personNumber'),
            $data['clientNumber'] ?? throw new InvalidCommandArgumentException('clientNumber'),
            $data['fromDate'] ?? throw new InvalidCommandArgumentException('fromDate'),
            $data['toDate'] ?? throw new InvalidCommandArgumentException('toDate'),
        );
    }
}
