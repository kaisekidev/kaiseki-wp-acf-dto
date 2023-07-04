<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\ACF\Dto\Casts;

use Attribute;
use Kaiseki\WordPress\ACF\Dto\Castables\WpUsersCastable;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

use function is_array;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class WpUsersCast implements Cast
{
    /**
     * @param DataProperty $property
     * @param mixed        $value
     * @param array<mixed> $context
     *
     * @return WpUsersCastable
     */
    public function cast(DataProperty $property, mixed $value, array $context): WpUsersCastable
    {
        return self::castValue($value);
    }

    public static function castValue(mixed $value): WpUsersCastable
    {
        if (!is_array($value)) {
            return new WpUsersCastable([]);
        }

        $ids = [];
        $users = [];

        foreach ($value as $item) {
            $userId = WpUserCast::getUserId($item);
            if ($userId !== null) {
                $ids[] = $userId;
            }
            if (!($item instanceof \WP_User)) {
                continue;
            }

            $users[] = $item;
        }

        return new WpUsersCastable($ids, $users);
    }
}
