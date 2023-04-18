<?php

declare(strict_types=1);

namespace Enfil\Laravel\Helpers\Dto\Hydration;

use JetBrains\PhpStorm\Pure;

abstract class SimpleStatic implements StaticInterface
{
    final public function __construct()
    {
    }

    public static function fromArray(array $data): static
    {
        $instance = new static();

        Simple::hydrate($instance, $data);

        return $instance;
    }

    #[Pure]
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
