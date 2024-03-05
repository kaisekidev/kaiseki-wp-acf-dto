<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Respect\Validation\Validator;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function is_string;

class EmailCast implements Cast
{
    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return string|null
     */
    public function cast(DataProperty $property, mixed $value, array $context): ?string
    {
        return self::castValue($value);
    }

    public static function castValue(mixed $value): ?string
    {
        if (!is_string($value) || $value === '') {
            return null;
        }

        return Validator::email()->validate($value) ? $value : null;
    }
}
