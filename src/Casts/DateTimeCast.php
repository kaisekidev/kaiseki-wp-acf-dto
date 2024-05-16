<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use DateTimeImmutable;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

use function is_string;
use function wp_timezone;

class DateTimeCast implements Cast
{
    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return DateTimeImmutable|null
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?DateTimeImmutable
    {
        return self::castValue($value);
    }

    public static function castValue(mixed $value): ?DateTimeImmutable
    {
        if (!is_string($value) || $value === '') {
            return null;
        }

        return new DateTimeImmutable($value, wp_timezone());
    }
}
