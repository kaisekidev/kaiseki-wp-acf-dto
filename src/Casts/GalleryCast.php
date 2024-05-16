<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Kaiseki\WordPress\ACF\Dto\Castables\GalleryCastable;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class GalleryCast implements Cast
{
    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return GalleryCastable
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): GalleryCastable
    {
        return self::castValue($value);
    }

    public static function castValue(mixed $value): GalleryCastable
    {
        $ids = IdsCast::castValue($value);

        return new GalleryCastable($ids);
    }
}
