<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

use function is_string;

class StringCast implements Cast
{
    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return string|null
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?string
    {
        return self::castValue($value);
    }

    public static function castValue(mixed $value): ?string
    {
        return is_string($value) ? $value : null;
    }
}
