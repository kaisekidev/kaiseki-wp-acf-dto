<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

use function is_array;

class ArrayTypeCast implements Cast
{
    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return array<mixed>|null
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?array
    {
        return is_array($value) ? $value : null;
    }
}
