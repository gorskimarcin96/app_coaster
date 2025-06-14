<?php

namespace App\Shared;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract readonly class ID
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
