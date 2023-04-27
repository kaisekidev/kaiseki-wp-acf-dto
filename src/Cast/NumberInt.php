<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Cast;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function is_numeric;

class NumberInt implements Cast
{
    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return int|null
     */
    public function cast(DataProperty $property, mixed $value, array $context): ?int
    {
        return is_numeric($value) ? (int)$value : null;
    }
}
