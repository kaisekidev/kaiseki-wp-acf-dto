<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Cast;

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
        if (
            !is_array($value)
            || !isset($value['title'], $value['url'], $value['target'])
        ) {
            return null;
        }
        return new LinkCastable(
            $value['title'],
            $value['url'],
            $value['target'],
        );
    }
}
