<?php

namespace App\Coaster\Infrastructure\Service;

use App\Coaster\Domain\Service\Notifier as NotifierInterface;

final readonly class Notifier implements NotifierInterface
{
    public function notify(string $message): void
    {
        log_message('info', $message);
    }
}
