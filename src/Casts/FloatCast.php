<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function is_numeric;

class FloatCast implements Cast
{
    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return float|null
     */
    public function cast(DataProperty $property, mixed $value, array $context): ?float
    {
        return self::castValue($value);
    }

    public static function castValue(mixed $value): ?float
    {
        return is_numeric($value) ? (float)$value : null;
    }
}
