<?php

declare(strict_types=1);

namespace Enfil\Laravel\Helpers\Dto\Hydration;

class Simple
{
    public static function hydrate(object $data, array $source): void
    {
        foreach (get_class_vars($data::class) as $key => $property) {
            if (array_key_exists($key, $source)) {
                $data->{$key} = $source[$key];
            }
        }
    }
}
