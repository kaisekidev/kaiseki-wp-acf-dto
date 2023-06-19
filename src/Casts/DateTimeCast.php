<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function is_string;

class DateTimeCast implements Cast
{
    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return \Safe\DateTimeImmutable|null
     */
    public function cast(DataProperty $property, mixed $value, array $context): ?\Safe\DateTimeImmutable
    {
        if (!is_string($value) || $value === '') {
            return null;
        }
        return new \Safe\DateTimeImmutable($value, wp_timezone());
    }
}
