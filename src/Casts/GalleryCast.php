<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Cast;

use Kaiseki\WordPress\ACF\Dto\Castables\GalleryCastable;
use Kaiseki\WordPress\ACF\Dto\Casts\IdsCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class GalleryCast implements Cast
{
    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return GalleryCastable
     */
    public function cast(DataProperty $property, mixed $value, array $context): GalleryCastable
    {
        $ids = IdsCast::castValue($value);
        return new GalleryCastable($ids);
    }
}
