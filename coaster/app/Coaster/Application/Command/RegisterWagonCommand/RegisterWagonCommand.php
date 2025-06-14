<?php

namespace App\Coaster\Application\Command\RegisterWagonCommand;

use App\Coaster\Application\Command\Exception\InvalidCommandArgumentException;

final readonly class RegisterWagonCommand
{
    public function __construct(
        public int $numberOfPlaces,
        public float $speed,
        public string $coasterId,
    ) {
    }

    public static function fromArray(array $data): RegisterWagonCommand
    {
        return new RegisterWagonCommand(
            $data['numberOfPlaces'] ?? throw new InvalidCommandArgumentException('numberOfPlaces'),
            $data['speed'] ?? throw new InvalidCommandArgumentException('speed'),
            $data['coasterId'] ?? throw new InvalidCommandArgumentException('coasterId'),
        );
    }
}
