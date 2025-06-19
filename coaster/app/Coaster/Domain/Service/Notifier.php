<?php

namespace App\Coaster\Domain\Service;

interface Notifier
{
    public function notify(string $message): void;
}
