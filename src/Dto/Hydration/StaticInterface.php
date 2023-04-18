<?php

namespace Enfil\Laravel\Helpers\Dto\Hydration;

interface StaticInterface
{
    public static function fromArray(array $data): static;

    public function toArray(): array;
}
