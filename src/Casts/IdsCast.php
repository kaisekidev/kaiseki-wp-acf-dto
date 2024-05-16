<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

use function array_reduce;
use function is_array;

class IdsCast implements Cast
{
    /**
     * @param DataProperty    $property
     * @param mixed           $value
     * @param array<mixed>    $properties
     * @param CreationContext $context
     *
     * @return list<int>
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): array
    {
        return self::castValue($value);
    }

    /**
     * @param mixed $value
     *
     * @return list<int>
     */
    public static function castValue(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_reduce($value, function ($carry, $item) {
            $val = IdCast::castValue($item);
            if ($val !== null) {
                $carry[] = $val;
            }

            return $carry;
        }, []);
    }
}
