<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

use function is_array;
use function is_numeric;
use function is_object;

class IdCast implements Cast
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
        if (is_object($value) && isset($value->ID)) {
            return (int)$value->ID;
        }
        if (is_array($value) && isset($value['ID'])) {
            return (int)$value['ID'];
        }
        if (is_numeric($value) && $value !== '0') {
            return (int)$value;
        }

        return null;
    }
}
