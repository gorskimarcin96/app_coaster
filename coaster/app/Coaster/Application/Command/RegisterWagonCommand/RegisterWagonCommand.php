<?php

declare(strict_types=1);

namespace App\Coaster\Application\Command\RegisterWagonCommand;

use App\Coaster\Application\Command\Exception\InvalidCommandArgumentException;

final readonly class RegisterWagonCommand
{
    public function __construct(
        public int $seats,
        public float $speedInMetersPerSecond,
        public string $coasterId,
    ) {
    }

    public static function fromArray(array $data): RegisterWagonCommand
    {
        return new RegisterWagonCommand(
            $data['seats'] ?? throw new InvalidCommandArgumentException('seats'),
            $data['speedInMetersPerSecond'] ?? throw new InvalidCommandArgumentException('speedInMetersPerSecond'),
            $data['coasterId'] ?? throw new InvalidCommandArgumentException('coasterId'),
        );
    }
}
