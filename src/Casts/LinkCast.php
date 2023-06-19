<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Kaiseki\WordPress\ACF\Dto\DataObjects\Link;
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
     * @return Link|null
     */
    public function cast(DataProperty $property, mixed $value, array $context): ?Link
    {
        return is_array($value) ? Link::from($value) : null;
    }
}
