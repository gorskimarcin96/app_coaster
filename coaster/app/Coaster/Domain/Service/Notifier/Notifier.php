<?php

namespace App\Coaster\Domain\Service\Notifier;

interface Notifier
{
    public function notify(string $message): void;
}
