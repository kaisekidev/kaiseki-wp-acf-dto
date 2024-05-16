<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class TrueFalseCast implements Cast
{
    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return bool
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): bool
    {
        return self::castValue($value);
    }

    public static function castValue(mixed $value): bool
    {
        return (bool)$value;
    }
}
