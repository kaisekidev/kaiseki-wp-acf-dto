<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Kaiseki\WordPress\ACF\Dto\Castables\GoogleMapCastable;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function is_array;

class GoogleMapCast implements Cast
{
    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return GoogleMapCastable|null
     */
    public function cast(DataProperty $property, mixed $value, array $context): ?GoogleMapCastable
    {
        return is_array($value) ? GoogleMapCastable::from($value) : null;
    }
}
