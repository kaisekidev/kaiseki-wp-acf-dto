<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Respect\Validation\Validator;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function is_string;

class TrueFalseCast implements Cast
{
    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return bool
     */
    public function cast(DataProperty $property, mixed $value, array $context): bool
    {
        return self::castValue($value);
    }

    public static function castValue(mixed $value): bool
    {
        return (bool)$value;
    }
}
