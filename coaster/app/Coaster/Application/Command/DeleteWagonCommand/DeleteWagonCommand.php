<?php

declare(strict_types=1);

namespace App\Coaster\Application\Command\DeleteWagonCommand;

final readonly class DeleteWagonCommand
{
    public function __construct(
        public string $coasterId,
        public string $wagonId,
    ) {
    }
}
