<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Kaiseki\WordPress\ACF\Dto\Castables\Link;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

use function is_array;

class LinkCast implements Cast
{
    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return Link|null
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?Link
    {
        return self::castValue($value);
    }

    public static function castValue(mixed $value): ?Link
    {
        if (!is_array($value)) {
            return null;
        }

        return Link::from($value);
    }
}
