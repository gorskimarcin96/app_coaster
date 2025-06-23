<?php

declare(strict_types=1);

namespace App\Shared;

use Stringable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract readonly class ID implements Stringable
{
    public function __construct(private UuidInterface $id)
    {
    }

    public function __toString(): string
    {
        return $this->id->toString();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public static function generate(): static
    {
        return new static(Uuid::uuid4());
    }
}
