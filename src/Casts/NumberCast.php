<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

use function ctype_digit;
use function is_float;
use function is_int;
use function is_numeric;
use function str_contains;

class NumberCast implements Cast
{
    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return float|int|null
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): int|float|null
    {
        // @phpstan-ignore argument.type
        return self::castValue($value, $property->hasDefaultValue ? $property->defaultValue : null);
    }

    public static function castValue(mixed $value, int|float|null $default = null): int|float|null
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }
        if (!is_numeric($value)) {
            return $default;
        }

        return ctype_digit($value) || (!str_contains($value, '.') && !str_contains($value, ','))
            ? (int)$value
            : (float)$value;
    }
}
