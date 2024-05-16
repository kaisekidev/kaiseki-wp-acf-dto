<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

use function is_numeric;

class IntCast implements Cast
{
    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return int|null
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?int
    {
        return self::castValue($value);
    }

    public static function castValue(mixed $value): ?int
    {
        return is_numeric($value) ? (int)$value : null;
    }
}
