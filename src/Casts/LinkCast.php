<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Kaiseki\WordPress\ACF\Dto\Castables\LinkCastable;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function is_array;

class LinkCast implements Cast
{
    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return LinkCastable|null
     */
    public function cast(DataProperty $property, mixed $value, array $context): ?LinkCastable
    {
        return self::castValue($value);
    }

    public static function castValue(mixed $value): ?LinkCastable
    {
        if (!is_array($value)) {
            return null;
        }

        return LinkCastable::from($value);
    }
}
